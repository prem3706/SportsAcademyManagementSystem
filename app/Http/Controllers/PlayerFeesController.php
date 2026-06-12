<?php

namespace App\Http\Controllers;

use App\DataTables\PlayerFeesDataTable;
use App\Models\PlayerFee;
use App\Models\Setting;
use App\Models\SportsLevel;
use App\Models\User;
use Illuminate\Http\Request;
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
        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get()
            ->mapWithKeys(function ($user) {
                return [$user->id => $user->firstname . ' ' . $user->lastname];
            })
            ->toArray();

        $currentYear = intval(date('Y'));
        $years = [];
        for ($y = $currentYear - 2; $y <= $currentYear + 2; $y++) {
            $years[$y] = (string)$y;
        }

        return $dataTable->render('playerFees.index', compact('players', 'years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get();

        return view('playerFees.createPlayerFeeForm', compact('players'));
    }

    /**
     * Get player enrolled batches with fee structure and discount settings.
     */
    public function getPlayerDetails($id)
    {
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
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'player_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'sub_totalamount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_amt' => 'required|numeric|min:0',
            'payment_type' => 'required|in:upi,cash,card',
            'status' => 'required|in:paid,pending',
        ];

        if ($request->payment_type === 'upi') {
            $rules['upi_id'] = 'required|string|max:255';
            $rules['img_upi'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        // Check for overlapping fee payments for this player
        $overlapping = PlayerFee::where('player_id', $request->player_id)
            ->where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date)
            ->exists();

        if ($overlapping) {
            throw ValidationException::withMessages([
                'start_date' => ['The player has already paid fees for the selected date range.'],
            ]);
        }

        $data = $request->only([
            'player_id',
            'start_date',
            'end_date',
            'sub_totalamount',
            'discount_amount',
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
        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get();

        return view('playerFees.editPlayerFeeForm', compact('playerFee', 'players'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlayerFee $playerFee)
    {
        $rules = [
            'player_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'sub_totalamount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_amt' => 'required|numeric|min:0',
            'payment_type' => 'required|in:upi,cash,card',
            'status' => 'required|in:paid,pending',
        ];

        if ($request->payment_type === 'upi') {
            $rules['upi_id'] = 'required|string|max:255';
            $rules['img_upi'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        // Check for overlapping fee payments for this player, excluding the current record
        $overlapping = PlayerFee::where('player_id', $request->player_id)
            ->where('id', '!=', $playerFee->id)
            ->where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date)
            ->exists();

        if ($overlapping) {
            throw ValidationException::withMessages([
                'start_date' => ['The player has already paid fees for the selected date range.'],
            ]);
        }

        $data = $request->only([
            'player_id',
            'start_date',
            'end_date',
            'sub_totalamount',
            'discount_amount',
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
}
