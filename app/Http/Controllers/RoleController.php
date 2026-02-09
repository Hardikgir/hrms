<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage roles');
        $roles = Role::withCount('users')->orderBy('name')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('manage roles');
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            // Group permissions by their prefix (e.g., 'view', 'create', 'manage')
            $parts = explode(' ', $permission->name);
            return $parts[0];
        });
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage roles');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,NULL,id,guard_name,web',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $this->authorize('manage roles');
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0];
        });
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('manage roles');
        
        // Prevent editing Super Admin role name
        if ($role->name === 'Super Admin' && $request->name !== 'Super Admin') {
            return redirect()->route('roles.edit', $role)
                ->with('error', 'Cannot change Super Admin role name.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id . ',id,guard_name,web',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);
        
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('manage roles');
        
        // Prevent deleting Super Admin role
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete Super Admin role.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete role: it is assigned to ' . $role->users()->count() . ' user(s).');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
