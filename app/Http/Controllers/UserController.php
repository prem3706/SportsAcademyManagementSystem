<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     **/
    public function index(UsersDataTable $dataTable)
    {
        abort_if(! Auth::user()->can('user_view'), 403);

        try {
            $roles = Role::pluck('name', 'name');

            return $dataTable->render('user.index', compact('roles'));
        } catch (Exception $e) {
            Log::error('User Index Error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('user_create'), 403);

        try {
            return view('user.addUserForm');
        } catch (Exception $e) {
            Log::error('User Create Form Error: '.$e->getMessage());

            return abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        abort_if(! Auth::user()->can('user_create'), 403);

        try {
            $validatedData = $request->validated();

            DB::transaction(function () use ($validatedData) {
                $user = User::create($validatedData);
                $user->assignRole($validatedData['role']);
            });

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('User Store Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
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
        abort_if(! Auth::user()->can('user_edit'), 403);

        try {
            return view('user.editUserForm', compact('user'));
        } catch (Exception $e) {
            Log::error('User Edit Form Error: '.$e->getMessage());

            return abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        abort_if(! Auth::user()->can('user_edit'), 403);

        try {
            $validatedData = $request->validated();

            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            }

            DB::transaction(function () use ($user, $validatedData) {
                $user->update($validatedData);
                $user->syncRoles([$validatedData['role']]);
            });

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('User Update Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort_if(! Auth::user()->can('user_delete'), 403);

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('User Delete Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        return handleBulkDelete($request, User::class, 'users', 'user_delete');
    }

    public function bulkUpdate(Request $request)
    {
        return handleBulkUpdate($request, User::class, 'users', 'user_edit');
    }
}
