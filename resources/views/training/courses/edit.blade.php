@extends('layouts.adminlte')
@section('title', __('messages.edit_course'))
@section('page_title', __('messages.edit_course'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('training.courses.index') }}">{{ __('messages.training_courses') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.edit_course') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('training.courses.update', $course) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group"><label for="name">{{ __('messages.name') }} *</label><input type="text"
                        class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', $course->name) }}" required>@error('name')<span
                        class="invalid-feedback">{{ $message }}</span>@enderror</div>
                <div class="form-group"><label for="description">{{ __('messages.description') }}</label><textarea
                        class="form-control" id="description" name="description"
                        rows="2">{{ old('description', $course->description) }}</textarea></div>
                <div class="form-group"><label for="duration_hours">{{ __('messages.duration') }}
                        ({{ __('messages.hrs') }})</label><input type="number" step="0.5" class="form-control"
                        id="duration_hours" name="duration_hours"
                        value="{{ old('duration_hours', $course->duration_hours) }}"></div>
                <div class="form-group"><label for="type">{{ __('messages.type') }}</label><input type="text"
                        class="form-control" id="type" name="type" value="{{ old('type', $course->type) }}"></div>
                <div class="form-group"><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}> {{ __('messages.active') }}</label></div>
                <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                <a href="{{ route('training.courses.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection