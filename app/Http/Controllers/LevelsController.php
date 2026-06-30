<?php

namespace App\Http\Controllers;

use App\DataTables\LevelsDataTable;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class LevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LevelsDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('level_view'), 403);

        try {
            return $dataTable->render('levels.index');
        } catch (Exception $e) {
            Log::error('Level Index Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('level_create'), 403);

        try {
            return view('levels.addLevelForm');
        } catch (Exception $e) {
            Log::error('Level Create Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('level_create'), 403);

        try {
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
        } catch (Exception $e) {
            Log::error('Level Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
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
    public function edit(Level $level)
    {
        abort_if(! Auth::user()->can('level_edit'), 403);

        try {
            return view('levels.editLevelForm', compact('level'));
        } catch (Exception $e) {
            Log::error('Level Edit Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        abort_if(! Auth::user()->can('level_edit'), 403);

        try {
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
        } catch (Exception $e) {
            Log::error('Level Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        abort_if(! Auth::user()->can('level_delete'), 403);

        try {
            $level->delete();

            return response()->json([
                'success' => true,
                'message' => 'Level deleted successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Level Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        return handleBulkDelete($request, Level::class, 'Levels', 'level_delete');
    }

    public function bulkUpdate(Request $request)
    {
        return handleBulkUpdate($request, Level::class, 'Levels', 'level_edit');
    }
}
