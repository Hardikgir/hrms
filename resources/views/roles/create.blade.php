@extends('layouts.adminlte')

@section('title', 'Create Role')
@section('page_title', 'Create Role')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Role</h3></div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Role Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required maxlength="255" 
                       placeholder="e.g. Manager, Finance, Recruiter">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Permissions</label>
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
                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
                <small class="text-muted">Select the permissions this role should have.</small>
            </div>

            <button type="submit" class="btn btn-primary">Create Role</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
