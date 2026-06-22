<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        return view('settings.addroleform');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! Auth::user()->can('setting_create'), 403);

        $request->merge([
            'name' => strtolower(trim($request->input('name'))),
        ]);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ], [
            'name.unique' => 'This role name has already been taken.',
        ]);

        Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_if(! Auth::user()->can('setting_edit'), 403);

        $role = Role::findOrFail($id);

        // Prevent editing default roles
        if (in_array(strtolower($role->name), $this->defaultRoles)) {
            abort(403, 'Default roles cannot be modified.');
        }

        return view('settings.editroleform', compact('role'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        abort_if(! Auth::user()->can('setting_edit'), 403);

        $role = Role::findOrFail($id);

        // Eager load or fetch permissions grouped by module_name
        $permissionsByModule = Permission::all()->groupBy('module_name');
        Log::info($permissionsByModule);

        return view('settings.permissions-table', [
            'selectedRole' => $role,
            'permissionsByModule' => $permissionsByModule,
        ]);

        // return view('roles.show', compact('role', 'permissionsByModule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        abort_if(! Auth::user()->can('setting_edit'), 403);

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

        $request->merge([
            'name' => strtolower(trim($request->input('name'))),
        ]);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
        ], [
            'name.unique' => 'This role name has already been taken.',
        ]);

        $role->update([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        abort_if(! Auth::user()->can('setting_delete'), 403);

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
    }
}
