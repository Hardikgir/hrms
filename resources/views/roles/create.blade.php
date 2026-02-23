@extends('layouts.adminlte')

@section('title', __('messages.create_role'))
@section('page_title', __('messages.create_role'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('messages.roles') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">{{ __('messages.new_role') }}</h3></div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="department_id">{{ __('messages.department') }}</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="">{{ __('messages.none_none') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="role_type">{{ __('messages.role_type') }}</label>
                        <select name="role_type" id="role_type" class="form-control">
                            <option value="">{{ __('messages.none_none') }}</option>
                            <option value="admin" {{ old('role_type') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ old('role_type') === 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="employee" {{ old('role_type') === 'employee' ? 'selected' : '' }}>Employee</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">{{ __('messages.role_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required maxlength="255"
                               placeholder="{{ __('messages.role_name_placeholder') }}">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <p class="text-muted small">{{ __('messages.role_department_help') }}</p>

            <div class="form-group">
                <label>{{ __('messages.permissions') }}</label>
                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                    @foreach($permissions as $group => $groupPermissions)
                        <div class="mb-3">
                            <h6 class="text-primary">{{ ucfirst($group) }}</h6>
                            <div class="row">
                                @foreach($groupPermissions as $permission)
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" 
                                                   id="permission_{{ $permission->id }}" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                                {{ \Illuminate\Support\Facades\Lang::has($permKey = 'messages.permission_' . str_replace(' ', '_', $permission->name)) ? __($permKey) : ucwords(str_replace('_', ' ', $permission->name)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
                <small class="text-muted">{{ __('messages.select_permissions_help') }}</small>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('messages.create_role') }}</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </form>
    </div>
</div>
@endsection
