<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.addUserForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // dd($request);
        $validatedData = $request->validated();

        // Hash Password
        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.editUserForm', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        if (! empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }

    /**
     * Bulk Delete Users
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('select', []);

        // Convert comma separated string into array
        if (! is_array($ids)) {

            $ids = array_filter(explode(',', $ids));
        }

        // Check selected users
        if (count($ids) > 0) {

            $deletedCount = User::destroy($ids);

            return response()->json([
                'success' => true,
                'message' => $deletedCount.' users deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No users selected.',
        ], 400);
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
            $updatedCount = User::whereIn('id', $ids)->update(['status' => $status]);

            return response()->json([
                'success' => true,
                'message' => $updatedCount.' users updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No valid users selected for update.',
        ], 422);
    }
}
