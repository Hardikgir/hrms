@extends('layouts.adminlte')

@section('title', __('messages.create_review_cycle'))
@section('page_title', __('messages.create_review_cycle'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.cycles.index') }}">{{ __('messages.review_cycles') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.review_cycles') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('performance.cycles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}" required maxlength="255">
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="period_start">{{ __('messages.period_start_label') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('period_start') is-invalid @enderror"
                                id="period_start" name="period_start" value="{{ old('period_start') }}" required>
                            @error('period_start')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="period_end">{{ __('messages.period_end_label') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('period_end') is-invalid @enderror"
                                id="period_end" name="period_end" value="{{ old('period_end') }}" required>
                            @error('period_end')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.create_cycle') }}</button>
                <a href="{{ route('performance.cycles.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection