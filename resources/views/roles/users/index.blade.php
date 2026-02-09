@extends('layouts.adminlte')

@section('title', 'User Roles')
@section('page_title', 'User Roles')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">User Roles</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Roles</h3>
        <div class="card-tools">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Roles</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">Assign roles to users to control their access and permissions.</p>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Employee</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->employee)
                            <span class="badge badge-info">{{ $user->employee->full_name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="badge badge-primary">{{ $role->name }}</span>
                        @empty
                            <span class="text-muted">No roles assigned</span>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('user-roles.edit', $user) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Manage Roles
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $users->links() }}</div>
    </div>
</div>
@endsection
