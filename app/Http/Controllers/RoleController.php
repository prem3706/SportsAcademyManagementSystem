<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class RoleController extends Controller
{
    /**
     * Default roles that are protected from editing and deletion.
     */
    protected array $defaultRoles = ['admin', 'manager', 'coach', 'player'];

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! Auth::user()->can('setting_create'), 403);

        try {
            return view('settings.addroleform');
        } catch (Exception $e) {
            Log::error('Role Create Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    public function store(RoleRequest $request)
    {
        abort_if(! Auth::user()->can('setting_create'), 403);

        try {
            Role::create([
                'name' => $request->input('name'),
                'guard_name' => 'web',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Role Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_if(! Auth::user()->can('setting_edit'), 403);

        try {
            $role = Role::findOrFail($id);

            // Prevent editing default roles
            if (in_array(strtolower($role->name), $this->defaultRoles)) {
                abort(403, 'Default roles cannot be modified.');
            }

            return view('settings.editroleform', compact('role'));
        } catch (Exception $e) {
            Log::error('Role Edit Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        abort_if(! Auth::user()->can('setting_edit'), 403);

        try {
            $role = Role::findOrFail($id);

            // Eager load or fetch permissions grouped by module_name
            $permissionsByModule = Permission::all()->groupBy('module_name');
            Log::info($permissionsByModule);

            return view('settings.permissions-table', [
                'selectedRole' => $role,
                'permissionsByModule' => $permissionsByModule,
            ]);
        } catch (Exception $e) {
            Log::error('Role Show Permissions Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        abort_if(! Auth::user()->can('setting_edit'), 403);

        try {
            $role = Role::findOrFail($id);

            // Check if this is a permission sync request
            if ($request->has('permissions_update')) {
                if ($role->name === 'admin') {
                    return redirect()->back()->with('error', 'The admin role permissions are protected and cannot be modified.');
                }

                $role->syncPermissions($request->input('permissions', []));

                return redirect()->back()->with('success', 'Permissions updated successfully.');
            }

            // Otherwise it is a role name update request (AJAX)
            // Prevent editing default roles
            if (in_array(strtolower($role->name), $this->defaultRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Default roles cannot be modified.',
                ], 403);
            }

            $role->update([
                'name' => $request->input('name'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Role Update Error: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Something went wrong.',
                ], 500);
            }
            return redirect()->back()->with('error', 'Something went wrong during update.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        abort_if(! Auth::user()->can('setting_delete'), 403);

        try {
            $role = Role::findOrFail($id);

            // Prevent deleting default roles
            if (in_array(strtolower($role->name), $this->defaultRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Default roles cannot be deleted.',
                ], 403);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Role Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }
}
