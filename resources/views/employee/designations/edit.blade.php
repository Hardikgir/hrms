@extends('layouts.adminlte')

@section('title', 'Edit Designation')
@section('page_title', 'Edit Designation')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item"><a href="{{ route('designations.index') }}">Designations</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Designation: {{ $designation->name }}</h3></div>
    <div class="card-body">
        <form action="{{ route('designations.update', $designation) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $designation->name) }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $designation->code) }}">
                        @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description', $designation->description) }}</textarea>
                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="department_id">Department</label>
                <select class="form-control" id="department_id" name="department_id">
                    <option value="">— None —</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $designation->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="min_salary">Min Salary</label>
                        <input type="number" step="0.01" class="form-control" id="min_salary" name="min_salary" value="{{ old('min_salary', $designation->min_salary) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="max_salary">Max Salary</label>
                        <input type="number" step="0.01" class="form-control" id="max_salary" name="max_salary" value="{{ old('max_salary', $designation->max_salary) }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $designation->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Active (show in dropdowns)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('designations.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
