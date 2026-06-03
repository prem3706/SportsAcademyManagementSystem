<?php

namespace App\Http\Controllers;

use App\DataTables\BatchesDataTable;
use App\Models\Batch;
use App\Models\Level;
use App\Models\Sport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BatchesDataTable $dataTable)
    {
        return $dataTable->render('batches.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Sports
        $sports = Sport::where('status', 'active')->get();

        // Levels
        $levels = Level::where('status', 'active')->get();

        // Coaches From Users Table
        $coaches = User::where('role', 'coach')
            ->where('status', 'active')
            ->get();

        // Players From Users Table
        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->get();

        // dd($sports, $levels, $coaches, $players);

        return view('batches.addBatchesForm', compact(

            'sports',

            'levels',

            'coaches',

            'players'

        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'capacity' => 'required|integer|min:1',

            'start_time' => 'required',

            'end_time' => 'required',

            'sport_id' => 'required|exists:sports,id',

            'level_id' => 'required|exists:levels,id',

            'coaches' => 'nullable|array',

            'coaches.*' => 'exists:users,id',

            'players' => 'nullable|array',

            'players.*' => 'exists:users,id',

            'status' => 'required|in:active,inactive',

        ]);
        $validated['start_time'] = Carbon::parse($request->start_time)
            ->format('H:i:s');

        $validated['end_time'] = Carbon::parse($request->end_time)
            ->format('H:i:s');
        if ($request->players && count($request->players) > $request->capacity) {

            return response()->json([

                'message' => 'Players limit exceeded.',

            ], 422);
        }

        $batch = Batch::create($validated);

        // Attach Coaches
        if ($request->has('coaches')) {

            $batch->coaches()->attach($request->coaches);
        }

        // Attach Players
        // Attach Players
        if ($request->has('players')) {

            foreach ($request->players as $playerId) {

                $batch->players()->attach($playerId, [

                    'joined_at' => now(),

                ]);
            }
        }

        return response()->json([

            'success' => true,

            'message' => 'Batch created successfully.',

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
    public function edit(Batch $batch)
    {
        // Sports
        $sports = Sport::where('status', 'active')->get();

        // Levels
        $levels = Level::where('status', 'active')->get();

        // Coaches From Users Table
        $coaches = User::where('role', 'coach')
            ->where('status', 'active')
            ->get();

        // Players From Users Table
        $players = User::where('role', 'player')
            ->where('status', 'active')
            ->get();

        return view('batches.editBatchesForm', compact('batch', 'sports', 'levels', 'coaches', 'players'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batch $batch)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'capacity' => 'required|integer|min:1',

            'start_time' => 'required',

            'end_time' => 'required',

            'sport_id' => 'required|exists:sports,id',

            'level_id' => 'required|exists:levels,id',

            'coaches' => 'nullable|array',

            'coaches.*' => 'exists:users,id',

            'players' => 'nullable|array',

            'players.*' => 'exists:users,id',

            'status' => 'required|in:active,inactive',

        ]);

        // Convert AM/PM time to database format
        $validated['start_time'] = Carbon::parse($request->start_time)
            ->format('H:i:s');

        $validated['end_time'] = Carbon::parse($request->end_time)
            ->format('H:i:s');

        // Update Batch
        $batch->update([

            'name' => $validated['name'],

            'capacity' => $validated['capacity'],

            'start_time' => $validated['start_time'],

            'end_time' => $validated['end_time'],

            'sport_id' => $validated['sport_id'],

            'level_id' => $validated['level_id'],

            'status' => $validated['status'],

        ]);

        // Sync Coaches
        $batch->coaches()->sync($request->coaches ?? []);

        // Sync Players
        // Sync Players With joined_at
        $syncPlayers = [];

        if ($request->has('players')) {

            foreach ($request->players as $playerId) {

                // Check if player already exists in this batch
                $existingPlayer = $batch->players()
                    ->where('player_id', $playerId)
                    ->first();

                // Old player → keep old joined_at
                if ($existingPlayer) {

                    $syncPlayers[$playerId] = [

                        'joined_at' => $existingPlayer->pivot->joined_at,

                    ];

                } else {

                    // New player → add current datetime
                    $syncPlayers[$playerId] = [

                        'joined_at' => now(),

                    ];
                }
            }
        }

        $batch->players()->sync($syncPlayers);

        return response()->json([

            'success' => true,

            'message' => 'Batch updated successfully.',

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Batch deleted successfully.',
        ]);
    }

    public function getSportLevels($id)
    {
        $sport = Sport::with('levels')->findOrFail($id);

        return response()->json($sport->levels);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('select', []);

        // Convert comma separated string into array
        if (! is_array($ids)) {

            $ids = array_filter(explode(',', $ids));
        }

        // Check selected users
        if (count($ids) > 0) {

            $deletedCount = Batch::destroy($ids);

            return response()->json([
                'success' => true,
                'message' => $deletedCount.' Batches deleted successfully.',
            ]);
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'select' => 'required',
            'status' => 'required|string|in:active,inactive',
        ]);

        $ids = $request->input('select', []);
        $status = $request->input('status');

        if (! is_array($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (count($ids) > 0) {
            $updatedCount = Batch::whereIn('id', $ids)->update(['status' => $status]);

            return response()->json([
                'success' => true,
                'message' => $updatedCount.' Batches updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid Batches selected for update.',
        ], 422);
    }
}
