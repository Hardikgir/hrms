@extends('layouts.adminlte')

@section('title', 'Roles')
@section('page_title', 'Roles')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Roles</h3>
        <div class="card-tools">
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Role</a>
            <a href="{{ route('user-roles.index') }}" class="btn btn-info btn-sm"><i class="fas fa-users"></i> Manage User Roles</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Roles define what permissions users have. Assign roles to users to control access.</p>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Users</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>
                        <strong>{{ $role->name }}</strong>
                        @if($role->name === 'Super Admin')
                            <span class="badge badge-danger ml-2">Protected</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $role->users_count }} user(s)</span>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $role->permissions->count() }} permission(s)</span>
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        @if($role->name !== 'Super Admin')
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No roles found. <a href="{{ route('roles.create') }}">Create one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
