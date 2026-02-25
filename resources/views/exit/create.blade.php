@extends('layouts.adminlte')
@section('title', __('messages.submit_resignation'))
@section('page_title', __('messages.submit_resignation'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('exit.index') }}">{{ __('messages.exit') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.submit_resignation') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('exit.store') }}" method="POST">
                @csrf
                <div class="form-group"><label for="resignation_date">{{ __('messages.resignation_date') }} *</label><input
                        type="date" class="form-control @error('resignation_date') is-invalid @enderror"
                        id="resignation_date" name="resignation_date" value="{{ old('resignation_date') }}"
                        required>@error('resignation_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group"><label for="last_working_date">{{ __('messages.last_working_day') }} *</label><input
                        type="date" class="form-control @error('last_working_date') is-invalid @enderror"
                        id="last_working_date" name="last_working_date" value="{{ old('last_working_date') }}"
                        required>@error('last_working_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group"><label for="reason">{{ __('messages.reason') }}</label><select class="form-control"
                        id="reason" name="reason">
                        <option value="">{{ __('messages.select') }}</option>
                        <option value="voluntary" {{ old('reason') == 'voluntary' ? 'selected' : '' }}>
                            {{ __('messages.voluntary') }}</option>
                        <option value="retirement" {{ old('reason') == 'retirement' ? 'selected' : '' }}>
                            {{ __('messages.retirement') }}</option>
                        <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}
                        </option>
                    </select></div>
                <div class="form-group"><label for="reason_details">{{ __('messages.reason_details') }}</label><textarea
                        class="form-control" id="reason_details" name="reason_details"
                        rows="3">{{ old('reason_details') }}</textarea></div>
                <button type="submit" class="btn btn-primary">{{ __('messages.submit') }}</button>
                <a href="{{ route('exit.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection