@extends('layouts.adminlte')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Role: {{ $role->name }}</h3>
        @if($role->name === 'Super Admin')
            <div class="alert alert-warning mt-2">
                <i class="fas fa-exclamation-triangle"></i> This is a protected role. The name cannot be changed.
            </div>
        @endif
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Role Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $role->name) }}" 
                       required maxlength="255" 
                       {{ $role->name === 'Super Admin' ? 'readonly' : '' }}>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Permissions</label>
                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="selectAll()">Select All</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAll()">Deselect All</button>
                </div>
                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                    @foreach($permissions as $group => $groupPermissions)
                        <div class="mb-3">
                            <h6 class="text-primary">{{ ucfirst($group) }}</h6>
                            <div class="row">
                                @foreach($groupPermissions as $permission)
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input permission-checkbox" 
                                                   id="permission_{{ $permission->id }}" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
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

            <button type="submit" class="btn btn-primary">Update Role</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function selectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.checked = true;
        });
    }
    
    function deselectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
    }
</script>
@endpush
@endsection
