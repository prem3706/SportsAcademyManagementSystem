<?php

namespace App\Http\Controllers;

use App\DataTables\SportsDataTable;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class SportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SportsDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('sport_view'), 403);

        try {
            return $dataTable->render('sports.index');
        } catch (Exception $e) {
            Log::error('Sport Index Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('sport_create'), 403);

        try {
            return view('sports.addSportForm');
        } catch (Exception $e) {
            Log::error('Sport Create Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('sport_create'), 403);

        try {
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
                    'errors' => $errors,
                ], 422);
            }

            Sport::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Sport created successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Sport Store Error: ' . $e->getMessage());
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
    public function edit(Sport $sport)
    {
        abort_if(! Auth::user()->can('sport_edit'), 403);

        try {
            return view('sports.editSportForm', compact('sport'));
        } catch (Exception $e) {
            Log::error('Sport Edit Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sport $sport)
    {
        abort_if(! Auth::user()->can('sport_edit'), 403);

        try {
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
                    'errors' => $errors,
                ], 422);
            }

            $sport->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Sport updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Sport Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sport $sport)
    {
        abort_if(! Auth::user()->can('sport_delete'), 403);

        try {
            $sport->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sports deleted successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Sport Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        abort_if(! Auth::user()->can('sport_delete'), 403);

        try {
            $ids = $request->input('select', []);

            if (! is_array($ids)) {
                $ids = array_filter(explode(',', $ids));
            }

            if (count($ids) > 0) {
                $deletedCount = Sport::destroy($ids);

                return response()->json([
                    'success' => true,
                    'message' => $deletedCount.' Sports deleted successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No sports selected.',
            ], 422);
        } catch (Exception $e) {
            Log::error('Sport Bulk Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        abort_if(! Auth::user()->can('sport_edit'), 403);

        try {
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
        } catch (Exception $e) {
            Log::error('Sport Bulk Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }
}
