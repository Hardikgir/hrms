@extends('layouts.adminlte')
@section('title', __('messages.new_shift'))
@section('page_title', __('messages.new_shift'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('shifts.index') }}">{{ __('messages.shifts') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.new_shift') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('shifts.store') }}" method="POST">
                @csrf
                <div class="form-group"><label for="name">{{ __('messages.name') }} *</label><input type="text"
                        class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}" required>@error('name')<span
                        class="invalid-feedback">{{ $message }}</span>@enderror</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="start_time">{{ __('messages.start_time') }} *</label><input
                                type="time" class="form-control" id="start_time" name="start_time"
                                value="{{ old('start_time', '09:00') }}" required></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label for="end_time">{{ __('messages.end_time') }} *</label><input
                                type="time" class="form-control" id="end_time" name="end_time"
                                value="{{ old('end_time', '18:00') }}" required></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label
                                for="break_duration">{{ __('messages.break_duration_min') }}</label><input type="number"
                                class="form-control" id="break_duration" name="break_duration"
                                value="{{ old('break_duration', 60) }}" min="0"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label for="working_hours">{{ __('messages.working_hrs') }}</label><input
                                type="number" class="form-control" id="working_hours" name="working_hours"
                                value="{{ old('working_hours', 8) }}" min="0" max="24"></div>
                    </div>
                </div>
                <div class="form-group"><label><input type="checkbox" name="is_flexible" value="1" {{ old('is_flexible') ? 'checked' : '' }}> {{ __('messages.flexible') }}</label></div>
                <div class="form-group"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> {{ __('messages.active') }}</label></div>
                <button type="submit" class="btn btn-primary">{{ __('messages.create') }}</button>
                <a href="{{ route('shifts.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection