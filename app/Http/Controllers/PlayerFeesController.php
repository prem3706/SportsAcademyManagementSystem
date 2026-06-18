<?php

namespace App\Http\Controllers;

use App\DataTables\PlayerFeesDataTable;
use App\Models\Batch;
use App\Models\PlayerFee;
use App\Models\Setting;
use App\Models\SportsLevel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PlayerFeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PlayerFeesDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('fee_view'), 403);
        // Log::info('PLAYER FEES REQUEST: ' . json_encode(request()->all()));
        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get()
            ->mapWithKeys(function ($user) {
                return [$user->id => $user->firstname.' '.$user->lastname];
            })
            ->toArray();

        $batches = Batch::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($batch) {
                return [$batch->id => $batch->name];
            })
            ->toArray();

        $currentYear = intval(date('Y'));
        $years = [];
        for ($y = $currentYear - 2; $y <= $currentYear + 2; $y++) {
            $years[$y] = (string) $y;
        }

        return $dataTable->render('playerFees.index', compact('players', 'years', 'batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('fee_create'), 403);
        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get();

        $preselected_player_id = request('player_id');
        $preselected_batch_id = request('batch_id');
        $preselected_month = request('month');
        $preselected_year = request('year');

        return view('playerFees.createPlayerFeeForm', compact(
            'players',
            'preselected_player_id',
            'preselected_batch_id',
            'preselected_month',
            'preselected_year'
        ));
    }

    /**
     * Get player enrolled batches with fee structure and discount settings.
     */
    public function getPlayerDetails($id)
    {
        abort_if(! Auth::user()->can('fee_view'), 403);
        $player = User::findOrFail($id);

        $batches = $player->playerBatches()->with(['sport', 'level'])->get();
        $batchesWithFees = $batches->map(function ($batch) {
            // Log::info($batch);

            $sportsLevel = SportsLevel::where('sport_id', $batch->sport_id)
                ->where('level_id', $batch->level_id)
                ->first();
            // Log::info($sportsLevel);

            $feeAmount = $sportsLevel ? floatval($sportsLevel->fees) : 0.00;
            // Log::info($feeAmount);

            return [
                'id' => $batch->id,
                'name' => $batch->name,
                'sport' => $batch->sport?->name ?? 'Unknown',
                'level' => $batch->level?->name ?? 'Unknown',
                'fees' => $feeAmount,
                'joined_at' => $batch->pivot->joined_at ? Carbon::parse($batch->pivot->joined_at)->toDateString() : null,
            ];
        });

        $settings = Setting::firstOrCreate(['id' => 1]);

        return response()->json([
            'batches' => $batchesWithFees,
            'discount_type' => $settings->discount_type,
            'discount_monthly' => floatval($settings->discount_monthly),
            'discount_quarterly' => floatval($settings->discount_quarterly),
            'discount_half_yearly' => floatval($settings->discount_half_yearly),
            'discount_yearly' => floatval($settings->discount_yearly),
            'penalty_allow' => $settings->allow_penalty,
            'penalty_days' => intval($settings->penalty_days),
            'penalty_type' => $settings->penalty_type,
            'penalty_amount' => floatval($settings->penalty_amount),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('fee_create'), 403);

        $rules = [
            'player_id' => 'required|exists:users,id',
            'batch_id' => 'required|exists:batches,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'sub_totalamount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'penalty_amount' => 'required|numeric|min:0',
            'total_amt' => 'required|numeric|min:0',
            'payment_type' => 'required|in:upi,cash,card',
            'status' => 'required|in:paid,pending',
        ];

        if ($request->payment_type === 'upi') {
            $rules['upi_id'] = 'required|string|max:255';
            $rules['img_upi'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        // Check for overlapping fee payments for this player and batch
        $overlapping = PlayerFee::where('player_id', $request->player_id)
            ->where('batch_id', $request->batch_id)
            ->where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date)
            ->exists();

        if ($overlapping) {
            throw ValidationException::withMessages([
                'start_date' => ['The player has already paid fees for the selected date range for this batch.'],
            ]);
        }

        $data = $request->only([
            'player_id',
            'batch_id',
            'start_date',
            'end_date',
            'sub_totalamount',
            'discount_amount',
            'penalty_amount',
            'total_amt',
            'payment_type',
            'status',
        ]);

        if ($request->payment_type === 'upi') {
            $data['upi_id'] = $request->upi_id;
            if ($request->hasFile('img_upi')) {
                $path = $request->file('img_upi')->store('receipts', 'public');
                $data['img_upi'] = 'storage/'.$path;
            }
        } else {
            $data['upi_id'] = null;
            $data['img_upi'] = null;
        }

        PlayerFee::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Player fee recorded successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlayerFee $playerFee)
    {
        abort_if(! Auth::user()->can('fee_edit'), 403);

        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get();

        $batchFee = 0.00;
        if ($playerFee->batch) {
            $sportsLevel = SportsLevel::where('sport_id', $playerFee->batch->sport_id)
                ->where('level_id', $playerFee->batch->level_id)
                ->first();
            $batchFee = $sportsLevel ? floatval($sportsLevel->fees) : 0.00;
        }

        return view('playerFees.editPlayerFeeForm', compact('playerFee', 'players', 'batchFee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlayerFee $playerFee)
    {
        abort_if(! Auth::user()->can('fee_edit'), 403);

        $rules = [
            'player_id' => 'required|exists:users,id',
            'batch_id' => 'required|exists:batches,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'sub_totalamount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'penalty_amount' => 'required|numeric|min:0',
            'total_amt' => 'required|numeric|min:0',
            'payment_type' => 'required|in:upi,cash,card',
            'status' => 'required|in:paid,pending',
        ];

        if ($request->payment_type === 'upi') {
            $rules['upi_id'] = 'required|string|max:255';
            $rules['img_upi'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        // Check for overlapping fee payments for this player and batch, excluding the current record
        $overlapping = PlayerFee::where('player_id', $request->player_id)
            ->where('batch_id', $request->batch_id)
            ->where('id', '!=', $playerFee->id)
            ->where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date)
            ->exists();

        if ($overlapping) {
            throw ValidationException::withMessages([
                'start_date' => ['The player has already paid fees for the selected date range for this batch.'],
            ]);
        }

        $data = $request->only([
            'player_id',
            'batch_id',
            'start_date',
            'end_date',
            'sub_totalamount',
            'discount_amount',
            'penalty_amount',
            'total_amt',
            'payment_type',
            'status',
        ]);

        if ($request->payment_type === 'upi') {
            $data['upi_id'] = $request->upi_id;
            if ($request->hasFile('img_upi')) {
                if ($playerFee->img_upi) {
                    $oldPath = str_replace('storage/', '', $playerFee->img_upi);
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('img_upi')->store('receipts', 'public');
                $data['img_upi'] = 'storage/'.$path;
            } else {
                $data['img_upi'] = $playerFee->img_upi;
            }
        } else {
            if ($playerFee->img_upi) {
                $oldPath = str_replace('storage/', '', $playerFee->img_upi);
                Storage::disk('public')->delete($oldPath);
            }
            $data['upi_id'] = null;
            $data['img_upi'] = null;
        }

        $playerFee->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Player fee updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlayerFee $playerFee)
    {
        abort_if(! Auth::user()->can('fee_delete'), 403);

        if ($playerFee->img_upi) {
            $oldPath = str_replace('storage/', '', $playerFee->img_upi);
            Storage::disk('public')->delete($oldPath);
        }

        $playerFee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Player fee deleted successfully.',
        ]);
    }

    /**
     * Check for overlapping player fee payments.
     */
    public function checkOverlap(Request $request)
    {
        abort_if(! Auth::user()->can('fee_create'), 403);

        $request->validate([
            'player_id' => 'required|exists:users,id',
            'batch_id' => 'required|exists:batches,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'exclude_id' => 'nullable|integer',
        ]);

        $query = PlayerFee::where('player_id', $request->player_id)
            ->where('batch_id', $request->batch_id)
            ->where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date);

        if ($request->filled('exclude_id')) {
            $query->where('id', '!=', $request->exclude_id);
        }

        $overlap = $query->first();

        if ($overlap) {
            $startFormatted = $overlap->start_date->format('F Y');
            $endFormatted = $overlap->end_date->format('F Y');

            return response()->json([
                'overlap' => true,
                'message' => "Player has already paid fees for the period: {$startFormatted} to {$endFormatted}.",
            ]);
        }

        return response()->json([
            'overlap' => false,
        ]);
    }
}
