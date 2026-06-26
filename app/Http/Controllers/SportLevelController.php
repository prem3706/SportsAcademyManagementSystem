<?php

namespace App\Http\Controllers;

use App\DataTables\SportsLevelsDataTable;
use App\Models\Level;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class SportLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SportsLevelsDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('sports_level_view'), 403);

        try {
            return $dataTable->render('sportLevel.index');
        } catch (Exception $e) {
            Log::error('SportLevel Index Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('sports_level_create'), 403);

        try {
            $sports = Sport::where('status', 'active')
                ->orderBy('name')
                ->get();

            $levels = Level::where('status', 'active')
                ->orderBy('name')
                ->get();

            return view('sportLevel.addSportslevelsForm', compact('sports', 'levels'))->render();
        } catch (Exception $e) {
            Log::error('SportLevel Create Form Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load create form.',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('sports_level_create'), 403);

        try {
            $request->validate([
                'sport_id' => 'required|exists:sports,id',
                'levels' => 'required|array',
                'levels.*.level_id' => 'required|exists:levels,id',
                'levels.*.fees' => 'required|numeric|min:0',
            ]);

            $sport = Sport::findOrFail($request->sport_id);

            foreach ($request->levels as $level) {
                $exists = $sport->levels()
                    ->where('level_id', $level['level_id'])
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This level is already added for this sport.',
                    ], 422);
                }

                $sport->levels()->attach(
                    $level['level_id'],
                    [
                        'fees' => $level['fees'],
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Sports Level created successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('SportLevel Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
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
    public function edit($id)
    {
        abort_if(! Auth::user()->can('sports_level_edit'), 403);

        try {
            $sport = Sport::with('levels')->findOrFail($id);

            $sports = Sport::where('status', 'active')
                ->orderBy('name')
                ->get();

            $levels = Level::where('status', 'active')
                ->orderBy('name')
                ->get();

            return view('sportLevel.editSportsLevelsForm', compact('sport', 'sports', 'levels'));
        } catch (Exception $e) {
            Log::error('SportLevel Edit Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        abort_if(! Auth::user()->can('sports_level_edit'), 403);

        try {
            $request->validate([
                'sport_id' => 'required|exists:sports,id',
                'levels' => 'required|array',
                'levels.*.level_id' => 'required|exists:levels,id',
                'levels.*.fees' => 'required|numeric|min:0',
            ]);

            // Find Sport
            $sport = Sport::findOrFail($id);

            // Prepare Sync Data
            $syncData = [];
            $levels = array_values($request->levels);

            foreach ($levels as $level) {
                $syncData[$level['level_id']] = [
                    'fees' => $level['fees'],
                ];
            }

            $sport->levels()->sync($syncData);

            return response()->json([
                'success' => true,
                'message' => 'Sport Levels updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('SportLevel Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
