<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('manage roles');
        
        $query = User::with('roles', 'employee')->orderBy('name');
        
        // Filter by role if provided
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }
        
        $users = $query->paginate(20)->withQueryString();
        $roles = Role::orderBy('name')->get();
        $selectedRole = $request->filled('role') ? Role::find($request->role) : null;
        
        return view('roles.users.index', compact('users', 'roles', 'selectedRole'));
    }

    public function edit(User $user)
    {
        $this->authorize('manage roles');
        
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('roles.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('manage roles');
        
        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($request->has('roles')) {
            $selectedRoles = Role::whereIn('id', $validated['roles'])->get();
            $user->syncRoles($selectedRoles);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('user-roles.index')->with('success', __('messages.user_roles_updated_success'));
    }
}
