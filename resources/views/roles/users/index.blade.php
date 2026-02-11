@extends('layouts.adminlte')

@section('title', __('messages.user_roles'))
@section('page_title', __('messages.user_roles'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('messages.roles') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.user_roles') }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.user_roles') }}</h3>
        <div class="card-tools">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> {{ __('messages.back_to_roles') }}</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">{{ __('messages.assign_roles_description') }}</p>
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
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.email') }}</th>
                    <th>{{ __('messages.employee') }}</th>
                    <th>{{ __('messages.roles') }}</th>
                    <th>{{ __('messages.actions') }}</th>
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
                            <span class="text-muted">{{ __('messages.no_roles_assigned') }}</span>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('user-roles.edit', $user) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> {{ __('messages.manage_roles') }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">{{ __('messages.no_users_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $users->links() }}</div>
    </div>
</div>
@endsection
