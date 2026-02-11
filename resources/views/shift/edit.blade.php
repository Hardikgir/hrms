@extends('layouts.adminlte')
@section('title', __('messages.edit_shift'))
@section('page_title', __('messages.edit_shift'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shifts.index') }}">{{ __('messages.shifts') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.edit_shift') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('shifts.update', $shift) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group"><label for="name">{{ __('messages.name') }} *</label><input type="text"
                        class="form-control" id="name" name="name" value="{{ old('name', $shift->name) }}" required></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="start_time">{{ __('messages.start_time') }} *</label><input
                                type="time" class="form-control" id="start_time" name="start_time"
                                value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}"
                                required></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label for="end_time">{{ __('messages.end_time') }} *</label><input
                                type="time" class="form-control" id="end_time" name="end_time"
                                value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}"
                                required></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label
                                for="break_duration">{{ __('messages.break_duration_min') }}</label><input type="number"
                                class="form-control" id="break_duration" name="break_duration"
                                value="{{ old('break_duration', $shift->break_duration) }}" min="0"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label for="working_hours">{{ __('messages.working_hrs') }}</label><input
                                type="number" class="form-control" id="working_hours" name="working_hours"
                                value="{{ old('working_hours', $shift->working_hours) }}" min="0" max="24"></div>
                    </div>
                </div>
                <div class="form-group"><label><input type="checkbox" name="is_flexible" value="1" {{ old('is_flexible', $shift->is_flexible) ? 'checked' : '' }}> {{ __('messages.flexible') }}</label></div>
                <div class="form-group"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $shift->is_active) ? 'checked' : '' }}> {{ __('messages.active') }}</label></div>
                <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                <a href="{{ route('shifts.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection