@extends('layouts.adminlte')

@section('title', 'Manage User Roles')
@section('page_title', 'Manage User Roles')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user-roles.index') }}">User Roles</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage Roles for: {{ $user->name }}</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Email:</strong> {{ $user->email }}<br>
            @if($user->employee)
                <strong>Employee:</strong> {{ $user->employee->full_name }}<br>
            @endif
            <strong>Current Roles:</strong>
            @forelse($user->roles as $role)
                <span class="badge badge-primary">{{ $role->name }}</span>
            @empty
                <span class="text-muted">No roles assigned</span>
            @endforelse
        </div>

        <form action="{{ route('user-roles.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Assign Roles</label>
                <div class="border rounded p-3">
                    @forelse($roles as $role)
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" 
                                   id="role_{{ $role->id }}" 
                                   name="roles[]" 
                                   value="{{ $role->id }}"
                                   {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="role_{{ $role->id }}">
                                <strong>{{ $role->name }}</strong>
                                @if($role->permissions->count() > 0)
                                    <small class="text-muted">({{ $role->permissions->count() }} permissions)</small>
                                @endif
                            </label>
                        </div>
                    @empty
                        <p class="text-muted">No roles available. <a href="{{ route('roles.create') }}">Create a role</a> first.</p>
                    @endforelse
                </div>
                <small class="text-muted">Select one or more roles to assign to this user.</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Roles</button>
            <a href="{{ route('user-roles.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
