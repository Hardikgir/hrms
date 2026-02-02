@extends('layouts.adminlte')
@section('title', 'New Course')
@section('page_title', 'New Course')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('training.courses.index') }}">Courses</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Training Course</h3></div>
    <div class="card-body">
        <form action="{{ route('training.courses.store') }}" method="POST">
            @csrf
            <div class="form-group"><label for="name">Name *</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>@error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label for="description">Description</label><textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea></div>
            <div class="form-group"><label for="duration_hours">Duration (hours)</label><input type="number" step="0.5" class="form-control" id="duration_hours" name="duration_hours" value="{{ old('duration_hours', 0) }}"></div>
            <div class="form-group"><label for="type">Type</label><input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}" placeholder="e.g. onboarding, compliance"></div>
            <div class="form-group"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> Active</label></div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('training.courses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
