@extends('layouts.ess')

@section('title', __('messages.new_travel_request'))
@section('page_title', __('messages.new_travel_request'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.travel') }}">{{ __('messages.travel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.new_request') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.new_travel_request') }}</h3>
            <div class="card-tools">
                <a href="{{ route('ess.travel') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_travel') }}</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('ess.travel.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="purpose">{{ __('messages.purpose') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('purpose') is-invalid @enderror" id="purpose"
                        name="purpose" value="{{ old('purpose') }}" required>
                    @error('purpose')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="destination">{{ __('messages.destination') }}</label>
                    <input type="text" class="form-control" id="destination" name="destination"
                        value="{{ old('destination') }}">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">{{ __('messages.start_date') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ old('start_date') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">{{ __('messages.end_date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ old('end_date') }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="estimated_amount">{{ __('messages.estimated_amount') }}</label>
                    <input type="number" step="0.01" class="form-control" id="estimated_amount" name="estimated_amount"
                        value="{{ old('estimated_amount') }}">
                </div>
                <div class="form-group">
                    <label for="notes">{{ __('messages.notes') }}</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.submit') }}</button>
                <a href="{{ route('ess.travel') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection