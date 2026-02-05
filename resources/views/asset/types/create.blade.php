@extends('layouts.adminlte')

@section('title', 'Add Asset Type')
@section('page_title', 'Add Asset Type')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Assets</a></li>
    <li class="breadcrumb-item"><a href="{{ route('asset-types.index') }}">Types</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Asset Type</h3></div>
    <div class="card-body">
        <form action="{{ route('asset-types.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="e.g. Laptop">
                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" maxlength="100" placeholder="e.g. laptop (leave blank to auto-generate)">
                <small class="text-muted">Unique identifier. Leave blank to generate from name.</small>
                @error('slug')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="sort_order">Sort order</label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">Active (available when creating assets)</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('asset-types.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
