@extends('layouts.adminlte')

@section('title', 'Add Employment Status')
@section('page_title', 'Add Employment Status')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employment-statuses.index') }}">Employment Statuses</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Employment Status</h3></div>
    <div class="card-body">
        <form action="{{ route('employment-statuses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="{{ __('messages.placeholder_active') }}">
                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" maxlength="100" placeholder="{{ __('messages.placeholder_slug_auto') }}">
                <small class="text-muted">Unique identifier. Leave blank to generate from name.</small>
                @error('slug')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="sort_order">Sort order</label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Active (available when creating employees)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('employment-statuses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
