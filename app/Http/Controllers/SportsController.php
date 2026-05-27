<?php

namespace App\Http\Controllers;

use App\DataTables\SportsDataTable;
use App\Models\Sport;
use Illuminate\Http\Request;

class SportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SportsDataTable $dataTable)
    {
        return $dataTable->render('sports.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sports.addSportForm');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([

            'name' => 'required|string|max:255',

            'slug' => 'required|string|max:255|unique:sports,slug',

            'description' => 'nullable|string|max:1000',

            'status' => 'required|in:active,inactive',

        ]);

        Sport::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Sport created successfully.',
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
    public function edit(Sport $sport)
    {
        return view('sports.editSportForm', compact('sport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sport $sport)
    {
        $validatedData = $request->validate([

            'name' => 'required|string|max:255',

            'slug' => 'required|string|max:255|unique:sports,slug,'.$sport->id,

            'description' => 'nullable|string|max:1000',

            'status' => 'required|in:active,inactive',

        ]);
        $sport->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Sport updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sport $sport)
    {
        $sport->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sport deleted successfully.',
        ]);
    }
}
