@extends('layouts.adminlte')
@section('title', 'Edit Shift')
@section('page_title', 'Edit Shift')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shifts.index') }}">Shifts</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Shift</h3></div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form action="{{ route('shifts.update', $shift) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group"><label for="name">Name *</label><input type="text" class="form-control" id="name" name="name" value="{{ old('name', $shift->name) }}" required></div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label for="start_time">Start Time *</label><input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}" required></div></div>
                <div class="col-md-6"><div class="form-group"><label for="end_time">End Time *</label><input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label for="break_duration">Break (minutes)</label><input type="number" class="form-control" id="break_duration" name="break_duration" value="{{ old('break_duration', $shift->break_duration) }}" min="0"></div></div>
                <div class="col-md-6"><div class="form-group"><label for="working_hours">Working Hours</label><input type="number" class="form-control" id="working_hours" name="working_hours" value="{{ old('working_hours', $shift->working_hours) }}" min="0" max="24"></div></div>
            </div>
            <div class="form-group"><label><input type="checkbox" name="is_flexible" value="1" {{ old('is_flexible', $shift->is_flexible) ? 'checked' : '' }}> Flexible</label></div>
            <div class="form-group"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $shift->is_active) ? 'checked' : '' }}> Active</label></div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
