<?php

namespace App\Http\Controllers;

use App\DataTables\PlayerFeesDataTable;
use App\Models\PlayerFee;
use App\Models\Sport;
use Illuminate\Http\Request;

class PlayerFeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PlayerFeesDataTable $dataTable)
    {
        $sports = Sport::pluck('name', 'id');

        return $dataTable->render(
            'playerFees.index',
            compact('sports')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

        return view('playerFees.editPlayerFeeForm', compact('playerFee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlayerFee $playerFee)
    {
        $request->validate([

            'status' => 'required|in:paid,unpaid',

        ]);

        // Update Player Fee
        $playerFee->update([

            'status' => $request->status,

            'paid_at' => $request->status == 'paid'
                ? now()
                : null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Fees Generate Status
        |--------------------------------------------------------------------------
        */

        $feesGenerate = $playerFee->feesGenerate;

        $totalFees = $feesGenerate->playerFees()->count();

        $paidFees = $feesGenerate->playerFees()
            ->where('status', 'paid')
            ->count();

        // All Paid
        if ($paidFees == $totalFees) {

            $feesGenerate->update([

                'status' => 'paid',

            ]);
        }

        // Partial Paid
        elseif ($paidFees > 0) {

            $feesGenerate->update([

                'status' => 'partial',

            ]);
        }

        // All Unpaid
        else {

            $feesGenerate->update([

                'status' => 'unpaid',

            ]);
        }

        return response()->json([

            'success' => true,

            'message' => 'Player fee updated successfully.',

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
