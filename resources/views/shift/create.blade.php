@extends('layouts.adminlte')
@section('title', 'New Shift')
@section('page_title', 'New Shift')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shifts.index') }}">Shifts</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Shift</h3></div>
    <div class="card-body">
        <form action="{{ route('shifts.store') }}" method="POST">
            @csrf
            <div class="form-group"><label for="name">Name *</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>@error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label for="start_time">Start Time *</label><input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', '09:00') }}" required></div></div>
                <div class="col-md-6"><div class="form-group"><label for="end_time">End Time *</label><input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', '18:00') }}" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label for="break_duration">Break (minutes)</label><input type="number" class="form-control" id="break_duration" name="break_duration" value="{{ old('break_duration', 60) }}" min="0"></div></div>
                <div class="col-md-6"><div class="form-group"><label for="working_hours">Working Hours</label><input type="number" class="form-control" id="working_hours" name="working_hours" value="{{ old('working_hours', 8) }}" min="0" max="24"></div></div>
            </div>
            <div class="form-group"><label><input type="checkbox" name="is_flexible" value="1" {{ old('is_flexible') ? 'checked' : '' }}> Flexible</label></div>
            <div class="form-group"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> Active</label></div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
