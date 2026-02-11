@extends('layouts.adminlte')

@section('title', __('messages.roles'))
@section('page_title', __('messages.roles'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.roles') }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.roles') }}</h3>
        <div class="card-tools">
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> {{ __('messages.add_role') }}</a>
            <a href="{{ route('user-roles.index') }}" class="btn btn-info btn-sm"><i class="fas fa-users"></i> {{ __('messages.manage_user_roles') }}</a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">{{ __('messages.roles_description') }}</p>
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
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.user') }}</th>
                    <th>{{ __('messages.permissions') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>
                        <strong>{{ $role->name }}</strong>
                        @if($role->name === 'Super Admin')
                            <span class="badge badge-danger ml-2">{{ __('messages.protected') }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $role->users_count }} {{ __('messages.users_count') }}</span>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $role->permissions->count() }} {{ __('messages.permissions_count') }}</span>
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        @if($role->name !== 'Super Admin')
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_role_confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">{{ __('messages.no_roles_found') }} <a href="{{ route('roles.create') }}">{{ __('messages.create_one') }}</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
