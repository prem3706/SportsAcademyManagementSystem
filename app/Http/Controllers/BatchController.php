<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Level;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('batches.index');
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

        $batch = Batch::create($validated);

        // Attach Coaches
        if ($request->has('coaches')) {

            $batch->coaches()->attach($request->coaches);
        }

        // Attach Players
        if ($request->has('players')) {

            $batch->players()->attach($request->players);
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

    public function getSportLevels($id)
    {
        $sport = Sport::with('levels')->findOrFail($id);

        return response()->json($sport->levels);
    }
}
