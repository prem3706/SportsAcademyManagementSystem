<?php

namespace App\Http\Controllers;

use App\DataTables\LevelsDataTable;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LevelsDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('level_view'), 403);

        return $dataTable->render('levels.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('level_create'), 403);

        return view('levels.addLevelForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('level_create'), 403);
        $request->merge([
            'slug' => Str::slug($request->input('name')),
        ]);

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:levels,slug',
            'status' => 'required|in:active,inactive',
        ], [
            'slug.unique' => 'This level name has already been taken.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('slug')) {
                $errors->add('name', $errors->first('slug'));
            }

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        Level::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Level created successfully.',
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
    public function edit(Level $level)
    {
        abort_if(! Auth::user()->can('level_edit'), 403);

        return view('levels.editLevelForm', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        abort_if(! Auth::user()->can('level_edit'), 403);
        $request->merge([
            'slug' => Str::slug($request->input('name')),
        ]);

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:levels,slug,'.$level->id,
            'status' => 'required|in:active,inactive',
        ], [
            'slug.unique' => 'This level name has already been taken.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('slug')) {
                $errors->add('name', $errors->first('slug'));
            }

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $level->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Level updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        abort_if(! Auth::user()->can('level_delete'), 403);
        $level->delete();

        return response()->json([
            'success' => true,
            'message' => 'Level deleted successfully.',
        ]);
    }

    public function bulkDelete(Request $request)
    {
        abort_if(! Auth::user()->can('level_delete'), 403);
        $ids = $request->input('select', []);

        // Convert comma separated string into array
        if (! is_array($ids)) {

            $ids = array_filter(explode(',', $ids));
        }

        // Check selected users
        if (count($ids) > 0) {

            $deletedCount = Level::destroy($ids);

            return response()->json([
                'success' => true,
                'message' => $deletedCount.' Sports deleted successfully.',
            ]);
        }
    }

    public function bulkUpdate(Request $request)
    {
        abort_if(! Auth::user()->can('level_edit'), 403);
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
            $updatedCount = Level::whereIn('id', $ids)->update(['status' => $status]);

            return response()->json([
                'success' => true,
                'message' => $updatedCount.' Levels updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid Levels selected for update.',
        ], 422);
    }
}
