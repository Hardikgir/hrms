@extends('layouts.adminlte')

@section('title', __('messages.manage_roles'))
@section('page_title', __('messages.manage_roles'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('messages.roles') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user-roles.index') }}">{{ __('messages.user_roles') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.manage_roles_for', ['name' => $user->name]) }}</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>{{ __('messages.email_label') }}:</strong> {{ $user->email }}<br>
            @if($user->employee)
                <strong>{{ __('messages.employee_label') }}:</strong> {{ $user->employee->full_name }}<br>
            @endif
            <strong>{{ __('messages.current_roles') }}:</strong>
            @forelse($user->roles as $role)
                <span class="badge badge-primary">{{ $role->name }}</span>
            @empty
                <span class="text-muted">{{ __('messages.no_roles_assigned') }}</span>
            @endforelse
        </div>

        <form action="{{ route('user-roles.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>{{ __('messages.assign_roles') }}</label>
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
                                    <small class="text-muted">({{ $role->permissions->count() }} {{ __('messages.permissions_count_label') }})</small>
                                @endif
                            </label>
                        </div>
                    @empty
                        <p class="text-muted">{{ __('messages.no_roles_available') }} <a href="{{ route('roles.create') }}">{{ __('messages.create_role_first') }}</a>.</p>
                    @endforelse
                </div>
                <small class="text-muted">{{ __('messages.select_roles_help') }}</small>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('messages.update_roles') }}</button>
            <a href="{{ route('user-roles.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
