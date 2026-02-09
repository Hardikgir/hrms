@extends('layouts.adminlte')

@section('title', 'Add Designation')
@section('page_title', 'Add Designation')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item"><a href="{{ route('designations.index') }}">Designations</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Designation</h3></div>
    <div class="card-body">
        <form action="{{ route('designations.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Developer, Manager">
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="e.g. DEV, MGR">
                        @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="department_id">Department</label>
                <select class="form-control" id="department_id" name="department_id">
                    <option value="">— None —</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="min_salary">Min Salary</label>
                        <input type="number" step="0.01" class="form-control" id="min_salary" name="min_salary" value="{{ old('min_salary') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="max_salary">Max Salary</label>
                        <input type="number" step="0.01" class="form-control" id="max_salary" name="max_salary" value="{{ old('max_salary') }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Active (show in dropdowns)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('designations.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
