<?php

namespace App\Http\Controllers;

use App\DataTables\SportsDataTable;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $request->merge([
            'slug' => Str::slug($request->input('name')),
        ]);

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sports,slug',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ], [
            'slug.unique' => 'This sport name has already been taken.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('slug')) {
                $errors->add('name', $errors->first('slug'));
            }
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors
            ], 422);
        }

        Sport::create($validator->validated());

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
        $request->merge([
            'slug' => Str::slug($request->input('name')),
        ]);

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sports,slug,'.$sport->id,
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ], [
            'slug.unique' => 'This sport name has already been taken.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('slug')) {
                $errors->add('name', $errors->first('slug'));
            }
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors
            ], 422);
        }

        $sport->update($validator->validated());

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
            'message' => 'Sports deleted successfully.',
        ]);
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

            $deletedCount = Sport::destroy($ids);

            return response()->json([
                'success' => true,
                'message' => $deletedCount.' Sports deleted successfully.',
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
            $updatedCount = Sport::whereIn('id', $ids)->update(['status' => $status]);

            return response()->json([
                'success' => true,
                'message' => $updatedCount.' Sports updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid Sports selected for update.',
        ], 422);
    }
}
