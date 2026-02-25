@extends('layouts.adminlte')

@section('title', __('messages.create_leave_type'))
@section('page_title', __('messages.create_leave_type'))

@section('content')
<div class="row form-container">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('messages.leave_type_details') }}</h3>
            </div>
            
            <form action="{{ route('leave-types.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label for="code">{{ __('messages.code') }} <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                            @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="form-group border-bottom pb-3 mb-3">
                        <label for="description">{{ __('messages.description') }}</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="max_days_per_year">{{ __('messages.max_days_per_year') }}</label>
                            <input type="number" name="max_days_per_year" id="max_days_per_year" min="0" max="365" class="form-control @error('max_days_per_year') is-invalid @enderror" value="{{ old('max_days_per_year') }}">
                            <small class="form-text text-muted">{{ __('messages.leave_blank_for_unlimited') }}</small>
                            @error('max_days_per_year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="carry_forward_limit">{{ __('messages.carry_forward_limit') }}</label>
                            <input type="number" name="carry_forward_limit" id="carry_forward_limit" min="0" class="form-control @error('carry_forward_limit') is-invalid @enderror" value="{{ old('carry_forward_limit') }}">
                            @error('carry_forward_limit')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row border-top pt-3">
                        <div class="col-md-6">
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" name="is_paid" id="is_paid" class="custom-control-input" value="1" {{ old('is_paid', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_paid">{{ __('messages.is_paid_leave') }}</label>
                            </div>
                            
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" name="requires_approval" id="requires_approval" class="custom-control-input" value="1" {{ old('requires_approval', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="requires_approval">{{ __('messages.requires_approval') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" name="can_carry_forward" id="can_carry_forward" class="custom-control-input" value="1" {{ old('can_carry_forward') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="can_carry_forward">{{ __('messages.can_carry_forward') }}</label>
                            </div>
                            
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">{{ __('messages.is_active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('leave-types.index') }}" class="btn btn-default">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
