@extends('layouts.adminlte')
@section('title', 'Edit Course')
@section('page_title', 'Edit Course')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('training.courses.index') }}">Courses</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Course</h3></div>
    <div class="card-body">
        <form action="{{ route('training.courses.update', $course) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group"><label for="name">Name *</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $course->name) }}" required>@error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label for="description">Description</label><textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $course->description) }}</textarea></div>
            <div class="form-group"><label for="duration_hours">Duration (hours)</label><input type="number" step="0.5" class="form-control" id="duration_hours" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours) }}"></div>
            <div class="form-group"><label for="type">Type</label><input type="text" class="form-control" id="type" name="type" value="{{ old('type', $course->type) }}"></div>
            <div class="form-group"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}> Active</label></div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('training.courses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
