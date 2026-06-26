<?php

namespace App\Http\Controllers;

use App\DataTables\BatchesDataTable;
use App\Models\Batch;
use App\Models\Level;
use App\Models\Sport;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BatchesDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('batch_view'), 403);

        try {
            return $dataTable->render('batches.index');
        } catch (Exception $e) {
            Log::error('Batch Index Error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Show the form for creating the resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('batch_create'), 403);

        try {
            $sports = Sport::where('status', 'active')->get();

            $levels = Level::where('status', 'active')->get();

            $coaches = User::where('role', 'coach')
                ->where('status', 'active')
                ->get();

            $players = User::where('role', 'player')
                ->where('status', 'active')
                ->get();

            return view(
                'batches.addBatchesForm',
                compact('sports', 'levels', 'coaches', 'players')
            );

        } catch (Exception $e) {
            Log::error('Batch Create Form Error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Store batch.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('batch_create'), 403);

        try {
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

            if ($request->players &&
                count($request->players) > $request->capacity) {

                return response()->json([
                    'message' => 'Players limit exceeded.',
                ], 422);
            }

            $batch = Batch::create($validated);

            if ($request->has('coaches')) {
                $batch->coaches()->attach($request->coaches);
            }

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

        } catch (Exception $e) {
            Log::error('Batch Store Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Edit batch.
     */
    public function edit(Batch $batch)
    {
        abort_if(! Auth::user()->can('batch_edit'), 403);

        try {
            $sports = Sport::where('status', 'active')->get();

            $levels = Level::where('status', 'active')->get();

            $coaches = User::where('role', 'coach')
                ->where('status', 'active')
                ->get();

            $players = User::where('role', 'player')
                ->where('status', 'active')
                ->get();

            return view(
                'batches.editBatchesForm',
                compact('batch', 'sports', 'levels', 'coaches', 'players')
            );

        } catch (Exception $e) {
            Log::error('Batch Edit Error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Update batch.
     */
    public function update(Request $request, Batch $batch)
    {
        abort_if(! Auth::user()->can('batch_edit'), 403);

        try {
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

            $batch->update([
                'name' => $validated['name'],
                'capacity' => $validated['capacity'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'sport_id' => $validated['sport_id'],
                'level_id' => $validated['level_id'],
                'status' => $validated['status'],
            ]);

            $batch->coaches()->sync($request->coaches ?? []);

            $syncPlayers = [];

            if ($request->has('players')) {
                foreach ($request->players as $playerId) {

                    $existingPlayer = $batch->players()
                        ->where('player_id', $playerId)
                        ->first();

                    if ($existingPlayer) {
                        $syncPlayers[$playerId] = [
                            'joined_at' => $existingPlayer->pivot->joined_at,
                        ];
                    } else {
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

        } catch (Exception $e) {
            Log::error('Batch Update Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Delete batch.
     */
    public function destroy(Batch $batch)
    {
        abort_if(! Auth::user()->can('batch_delete'), 403);

        try {
            $batch->delete();

            return response()->json([
                'success' => true,
                'message' => 'Batch deleted successfully.',
            ]);

        } catch (Exception $e) {
            Log::error('Batch Delete Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function getSportLevels($id)
    {
        abort_if(! Auth::user()->can('batch_view'), 403);

        try {
            $sport = Sport::with('levels')->findOrFail($id);

            return response()->json($sport->levels);

        } catch (Exception $e) {
            Log::error('Sport Levels Error: '.$e->getMessage());

            return response()->json([], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        abort_if(! Auth::user()->can('batch_delete'), 403);

        try {
            $ids = $request->input('select', []);

            if (! is_array($ids)) {
                $ids = array_filter(explode(',', $ids));
            }

            if (count($ids) > 0) {
                $deletedCount = Batch::destroy($ids);

                return response()->json([
                    'success' => true,
                    'message' => $deletedCount.' Batches deleted successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No batches selected.',
            ], 422);

        } catch (Exception $e) {
            Log::error('Bulk Delete Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        abort_if(! Auth::user()->can('batch_edit'), 403);

        try {
            $validated = $request->validate([
                'select' => 'required',
                'status' => 'required|in:active,inactive',
            ]);

            $ids = $request->input('select', []);
            $status = $request->input('status');

            if (! is_array($ids)) {
                $ids = array_filter(explode(',', $ids));
            }

            if (count($ids) > 0) {
                $updatedCount = Batch::whereIn('id', $ids)
                    ->update(['status' => $status]);

                return response()->json([
                    'success' => true,
                    'message' => $updatedCount.' Batches updated successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No valid batches selected.',
            ], 422);

        } catch (Exception $e) {
            Log::error('Bulk Update Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }
}
