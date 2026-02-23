@extends('layouts.ess')

@section('title', __('messages.edit_profile'))
@section('page_title', __('messages.edit_profile'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.profile') }}">{{ __('messages.profile') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_profile') }}</h3>
                </div>
                <form action="{{ route('ess.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <h5>{{ __('messages.personal_information') }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">{{ __('messages.phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                        name="phone" value="{{ old('phone', $employee->phone) }}">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">{{ __('messages.address') }}</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                                name="address" rows="2">{{ old('address', $employee->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">{{ __('messages.city') }}</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                        name="city" value="{{ old('city', $employee->city) }}">
                                    @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state">{{ __('messages.state') }}</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                                        name="state" value="{{ old('state', $employee->state) }}">
                                    @error('state')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="postal_code">{{ __('messages.postal_code') }}</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                        id="postal_code" name="postal_code"
                                        value="{{ old('postal_code', $employee->postal_code) }}">
                                    @error('postal_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>{{ __('messages.emergency_contact') }}</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_name">{{ __('messages.contact_name') }}</label>
                                    <input type="text"
                                        class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                        id="emergency_contact_name" name="emergency_contact_name"
                                        value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}">
                                    @error('emergency_contact_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_phone">{{ __('messages.contact_phone') }}</label>
                                    <input type="text"
                                        class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                        id="emergency_contact_phone" name="emergency_contact_phone"
                                        value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}">
                                    @error('emergency_contact_phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="emergency_contact_relation">{{ __('messages.relation') }}</label>
                                    <input type="text"
                                        class="form-control @error('emergency_contact_relation') is-invalid @enderror"
                                        id="emergency_contact_relation" name="emergency_contact_relation"
                                        value="{{ old('emergency_contact_relation', $employee->emergency_contact_relation) }}">
                                    @error('emergency_contact_relation')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.update_profile') }}
                        </button>
                        <a href="{{ route('ess.profile') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection