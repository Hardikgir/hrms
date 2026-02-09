@extends('layouts.adminlte')

@section('title', 'Edit Department')
@section('page_title', 'Edit Department')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Department: {{ $department->name }}</h3></div>
    <div class="card-body">
        <form action="{{ route('departments.update', $department) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $department->name) }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $department->code) }}">
                        @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description', $department->description) }}</textarea>
                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="parent_id">Parent Department</label>
                <select class="form-control" id="parent_id" name="parent_id">
                    <option value="">— None —</option>
                    @foreach($parentDepartments as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $department->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="manager_ids">Managers</label>
                <select class="form-control select2" id="manager_ids" name="manager_ids[]" multiple style="width: 100%;">
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ in_array($employee->id, old('manager_ids', $selectedManagers)) ? 'selected' : '' }}>
                            {{ $employee->full_name }} ({{ $employee->employee_id }})
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Select one or more managers for this department. You can select multiple.</small>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Active (show in dropdowns)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#manager_ids').select2({
            placeholder: 'Select managers...',
            allowClear: true
        });
    });
</script>
@endpush
