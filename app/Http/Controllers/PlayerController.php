<?php

namespace App\Http\Controllers;

use App\DataTables\PlayersDataTable;
use App\Models\Batch;
use App\Models\Level;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PlayersDataTable $dataTable)
    {
        $sports = Sport::where('status', 'active')->get();
        $levels = Level::where('status', 'active')->get();
        $batches = Batch::where('status', 'active')->get();

        return $dataTable->render('players.index', compact('sports', 'levels', 'batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sports = Sport::where('status', 'active')->get();

        return view('players.addPlayerForm', compact('sports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'joined_at' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'assignments' => 'required|array|min:1',
            'assignments.*.sport_id' => 'required|exists:sports,id',
            'assignments.*.level_id' => 'required|exists:levels,id',
            'assignments.*.batch_id' => 'required|exists:batches,id',
        ]);

        // Generate password: firstname@123 (lowercase)
        $plainPassword = strtolower(str_replace(' ', '', $request->firstname)).'@123';

        // Create user as active player
        $player = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $plainPassword,
            'gender' => $request->gender,
            'role' => 'player',
            'status' => 'active',
            'joined_at' => $request->joined_at,
        ]);

        // Attach player to all selected batches
        foreach ($request->assignments as $assignment) {
            $batch = Batch::findOrFail($assignment['batch_id']);
            $batch->players()->attach($player->id, [
                'joined_at' => $request->joined_at,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Player created successfully.',
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
        $player = User::findOrFail($id);
        $sports = Sport::where('status', 'active')->get();

        // Load player's batches with sport and level
        $playerBatches = $player->playerBatches()->with(['sport.levels', 'level'])->get();

        return view('players.editPlayerForm', compact('player', 'sports', 'playerBatches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $player = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$player->id,
            'phone' => 'required|string|max:20|unique:users,phone,'.$player->id,
            'joined_at' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'required|in:active,inactive',
            'assignments' => 'required|array|min:1',
            'assignments.*.batch_id' => 'required|exists:batches,id',
        ]);

        $player->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'status' => $request->status,
            'joined_at' => $request->joined_at,
        ]);

        // Sync batches
        $syncBatches = [];
        foreach ($request->assignments as $assignment) {
            $syncBatches[$assignment['batch_id']] = [
                'joined_at' => $request->joined_at,
            ];
        }
        $player->playerBatches()->sync($syncBatches);

        return response()->json([
            'success' => true,
            'message' => 'Player updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $player = User::findOrFail($id);
        $player->delete();

        return response()->json([
            'success' => true,
            'message' => 'Player deleted successfully.',
        ]);
    }

    /**
     * Get batches filtered by sport and level.
     */
    public function getBatches($sportId, $levelId)
    {
        $batches = Batch::where('sport_id', $sportId)
            ->where('level_id', $levelId)
            ->where('status', 'active')
            ->get();

        return response()->json($batches);
    }

    /**
     * Bulk Delete Players
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('select', []);

        if (! is_array($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (count($ids) > 0) {
            $deletedCount = User::destroy($ids);

            return response()->json([
                'success' => true,
                'message' => $deletedCount.' players deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No players selected.',
        ], 400);
    }

    /**
     * Bulk Status Update Players
     */
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
            $updatedCount = User::whereIn('id', $ids)->update(['status' => $status]);

            return response()->json([
                'success' => true,
                'message' => $updatedCount.' players updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid players selected for update.',
        ], 422);
    }
}
