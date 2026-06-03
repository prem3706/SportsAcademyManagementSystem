<?php

namespace App\Http\Controllers;

use App\DataTables\FeesGenerateDataTable;
use App\Models\FeesGenerate;
use App\Models\PlayerFee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeesGenerateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FeesGenerateDataTable $dataTable)
    {
        return $dataTable->render('feesGenerate.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('feesGenerate.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'month' => 'required|integer|min:1|max:12',

            'year' => 'required|integer|min:2024',

        ]);

        // Prevent Duplicate Generation
        $alreadyGenerated = FeesGenerate::where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($alreadyGenerated) {

            return response()->json([

                'success' => false,

                'message' => 'Fees already generated for this month.',

            ], 422);
        }

        // Create Fees Generate Record
        $feesGenerate = FeesGenerate::create([

            'month' => $request->month,

            'year' => $request->year,

            'status' => 'unpaid',

            'generated_by' => Auth::id(),

        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Player Fees
        |--------------------------------------------------------------------------
        */

        // Get All Players
        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->with('playerBatches.sport.levels')
            ->get();

        foreach ($players as $player) {

            /*
            |--------------------------------------------------------------------------
            | Group By Sport
            |--------------------------------------------------------------------------
            | One Sport = One Fees
            | Even if player joined multiple batches
            */

            $sports = [];

            foreach ($player->playerBatches as $batch) {

                $sportId = $batch->sport_id;

                // Skip if already added
                if (isset($sports[$sportId])) {
                    continue;
                }

                // Get Fees From sport_level Pivot
                $sportLevel = $batch->sport
                    ->levels()
                    ->where('levels.id', $batch->level_id)
                    ->first();

                if (! $sportLevel) {
                    continue;
                }

                $sports[$sportId] = [

                    'sport_id' => $batch->sport_id,

                    'level_id' => $batch->level_id,

                    'amount' => $sportLevel->pivot->fees,

                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Insert Fees
            |--------------------------------------------------------------------------
            */

            foreach ($sports as $feeData) {

                // Prevent Duplicate Fees
                $exists = PlayerFee::where('user_id', $player->id)
                    ->where('sport_id', $feeData['sport_id'])
                    ->where('month', $request->month)
                    ->where('year', $request->year)
                    ->exists();

                if ($exists) {
                    continue;
                }

                PlayerFee::create([

                    'fees_generate_id' => $feesGenerate->id,

                    'user_id' => $player->id,

                    'sport_id' => $feeData['sport_id'],

                    'level_id' => $feeData['level_id'],

                    'month' => $request->month,

                    'year' => $request->year,

                    'amount' => $feeData['amount'],

                    'status' => 'unpaid',

                    'generated_at' => now(),

                ]);
            }
        }

        return response()->json([

            'success' => true,

            'message' => 'Fees generated successfully.
            ',

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
