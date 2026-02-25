@extends('layouts.adminlte')

@section('title', __('messages.edit_review_cycle'))
@section('page_title', __('messages.edit_review_cycle'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.cycles.index') }}">{{ __('messages.review_cycles') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.review_cycles') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('performance.cycles.update', $cycle) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', $cycle->name) }}" required maxlength="255">
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">{{ __('messages.period_start') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                id="start_date" name="start_date"
                                value="{{ old('start_date', $cycle->period_start->format('Y-m-d')) }}" required>
                            @error('start_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">{{ __('messages.period_end') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                name="end_date" value="{{ old('end_date', $cycle->period_end->format('Y-m-d')) }}" required>
                            @error('end_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="draft" {{ old('status', $cycle->status) == 'draft' ? 'selected' : '' }}>
                                    {{ __('messages.draft') }}</option>
                                <option value="active" {{ old('status', $cycle->status) == 'active' ? 'selected' : '' }}>
                                    {{ __('messages.active') }}</option>
                                <option value="closed" {{ old('status', $cycle->status) == 'closed' ? 'selected' : '' }}>
                                    {{ __('messages.closed') }}</option>
                            </select>
                            @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                    <a href="{{ route('performance.cycles.index') }}"
                        class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection