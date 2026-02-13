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

    public function index()
    {
        $this->authorize('manage roles');
        
        $users = User::with('roles', 'employee')
            ->orderBy('name')
            ->paginate(20);
        
        $roles = Role::orderBy('name')->get();
        
        return view('roles.users.index', compact('users', 'roles'));
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
