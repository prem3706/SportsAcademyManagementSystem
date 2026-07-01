<?php

namespace App\Http\Controllers;

use App\DataTables\SportsDataTable;
use App\Http\Requests\SportRequest;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(SportRequest $request)
    {
        abort_if(! Auth::user()->can('sport_create'), 403);

        try {
            Sport::create($request->validated());

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
    public function update(SportRequest $request, Sport $sport)
    {
        abort_if(! Auth::user()->can('sport_edit'), 403);

        try {
            $sport->update($request->validated());

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
        return handleBulkDelete($request, Sport::class, 'Sports', 'sport_delete');
    }

    public function bulkUpdate(Request $request)
    {
        return handleBulkUpdate($request, Sport::class, 'Sports', 'sport_edit');
    }
}
