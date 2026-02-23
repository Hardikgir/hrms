<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Modules\Employee\Models\Department;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('manage roles');
        $query = Role::with('department')->withCount(['users', 'permissions'])->orderBy('name');
        
        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        // Filter by role type
        if ($request->filled('role_type')) {
            $query->where('role_type', $request->role_type);
        }
        
        $roles = $query->paginate(20)->withQueryString();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        
        // Statistics
        $totalRoles = Role::count();
        $totalUsers = \App\Models\User::has('roles')->count();
        $totalPermissions = Permission::count();
        
        return view('roles.index', compact('roles', 'departments', 'totalRoles', 'totalUsers', 'totalPermissions'));
    }

    public function create()
    {
        $this->authorize('manage roles');
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0];
        });
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('roles.create', compact('permissions', 'departments'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage roles');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'role_type' => 'nullable|in:admin,manager,employee',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Unique name per guard (and optionally per department for same guard)
        $exists = Role::where('name', $validated['name'])->where('guard_name', 'web')->first();
        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['name' => 'A role with this name already exists.']);
        }

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'department_id' => $validated['department_id'] ?? null,
            'role_type' => $validated['role_type'] ?? null,
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', __('messages.role_created_success'));
    }

    public function edit(Role $role)
    {
        $this->authorize('manage roles');
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0];
        });
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions', 'departments'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('manage roles');

        if ($role->name === 'Super Admin' && $request->name !== 'Super Admin') {
            return redirect()->route('roles.edit', $role)
                ->with('error', __('messages.role_cannot_change_super_admin'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'role_type' => 'nullable|in:admin,manager,employee',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $exists = Role::where('name', $validated['name'])->where('guard_name', 'web')->where('id', '!=', $role->id)->first();
        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['name' => 'A role with this name already exists.']);
        }

        $role->update([
            'name' => $validated['name'],
            'department_id' => $validated['department_id'] ?? null,
            'role_type' => $validated['role_type'] ?? null,
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', __('messages.role_updated_success'));
    }

    /**
     * Create default roles (Admin, Manager, Employee) for a department. Super Admin assigns permissions afterward.
     */
    public function createForDepartment(Request $request)
    {
        $this->authorize('manage roles');
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);
        $department = Department::findOrFail($validated['department_id']);
        $prefix = $department->name;
        $created = [];
        foreach (Role::roleTypes() as $type => $label) {
            $name = "{$prefix} {$label}";
            if (!Role::where('name', $name)->where('guard_name', 'web')->exists()) {
                Role::create([
                    'name' => $name,
                    'guard_name' => 'web',
                    'department_id' => $department->id,
                    'role_type' => $type,
                ]);
                $created[] = $name;
            }
        }
        if (empty($created)) {
            return redirect()->route('roles.index')->with('info', __('messages.role_default_exists', ['prefix' => $prefix]));
        }
        return redirect()->route('roles.index')->with('success', __('messages.role_created_for_department', ['list' => implode(', ', $created)]));
    }

    public function destroy(Role $role)
    {
        $this->authorize('manage roles');
        
        // Prevent deleting Super Admin role
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')
                ->with('error', __('messages.role_cannot_delete_super_admin'));
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', __('messages.role_cannot_delete_has_users', ['count' => $role->users()->count()]));
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', __('messages.role_deleted_success'));
    }
}
