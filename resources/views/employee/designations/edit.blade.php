@extends('layouts.adminlte')

@section('title', __('messages.edit_designation'))
@section('page_title', __('messages.edit_designation'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('messages.settings') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('designations.index') }}">{{ __('messages.designations') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.edit_designation') }}: {{ $designation->name }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('designations.update', $designation) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $designation->name) }}" required>
                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">{{ __('messages.code') }}</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
                                name="code" value="{{ old('code', $designation->code) }}">
                            @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">{{ __('messages.description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" rows="2">{{ old('description', $designation->description) }}</textarea>
                    @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="department_id">{{ __('messages.department') }}</label>
                    <select class="form-control" id="department_id" name="department_id">
                        <option value="">{{ __('messages.none_none') }}</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $designation->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="min_salary">{{ __('messages.min_salary') }}</label>
                            <input type="number" step="0.01" class="form-control" id="min_salary" name="min_salary"
                                value="{{ old('min_salary', $designation->min_salary) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="max_salary">{{ __('messages.max_salary') }}</label>
                            <input type="number" step="0.01" class="form-control" id="max_salary" name="max_salary"
                                value="{{ old('max_salary', $designation->max_salary) }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sidebar_color">{{ __('messages.sidebar_color') }}</label>
                    <div>
                        <input type="color" class="@error('sidebar_color') is-invalid @enderror" id="sidebar_color"
                            name="sidebar_color"
                            value="{{ old('sidebar_color', $designation->sidebar_color ?? '#343a40') }}"
                            style="display: block; width: 100%; max-width: 300px; height: 50px; border: 1px solid #ced4da; border-radius: 4px; cursor: pointer;">
                        @error('sidebar_color')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>
                    <small class="text-muted">{{ __('messages.sidebar_color_help') }}</small>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $designation->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">{{ __('messages.active_show_help') }}</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                <a href="{{ route('designations.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection