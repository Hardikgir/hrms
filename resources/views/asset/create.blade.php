@extends('layouts.adminlte')
@section('title', __('messages.new_asset'))
@section('page_title', __('messages.new_asset'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">{{ __('messages.assets') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.new_asset') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('assets.store') }}" method="POST">
                @csrf
                <div class="form-group"><label for="name">{{ __('messages.name') }} *</label><input type="text"
                        class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}" required>@error('name')<span
                        class="invalid-feedback">{{ $message }}</span>@enderror</div>
                <div class="form-group">
                    <label for="type">{{ __('messages.type') }} *</label>
                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">{{ __('messages.select_type') }}</option>
                        @foreach($assetTypes as $t)
                            <option value="{{ $t->slug }}" {{ old('type') === $t->slug ? 'selected' : '' }}>{{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    @if($assetTypes->isEmpty())
                        <small class="text-muted">{{ __('messages.no_asset_types_help') }} <a
                                href="{{ route('asset-types.create') }}">{{ __('messages.add_one') }}</a></small>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="serial_number">{{ __('messages.serial_number') }}</label><input
                                type="text" class="form-control" id="serial_number" name="serial_number"
                                value="{{ old('serial_number') }}"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label for="asset_tag">{{ __('messages.asset_tag') }}</label><input
                                type="text" class="form-control" id="asset_tag" name="asset_tag"
                                value="{{ old('asset_tag') }}"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="purchase_date">{{ __('messages.purchase_date') }}</label><input
                                type="date" class="form-control" id="purchase_date" name="purchase_date"
                                value="{{ old('purchase_date') }}"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label
                                for="purchase_value">{{ __('messages.purchase_value') }}</label><input type="number"
                                step="0.01" class="form-control" id="purchase_value" name="purchase_value"
                                value="{{ old('purchase_value') }}"></div>
                    </div>
                </div>
                <div class="form-group"><label for="location">{{ __('messages.location') }}</label><input type="text"
                        class="form-control" id="location" name="location" value="{{ old('location') }}"></div>
                <div class="form-group"><label for="notes">{{ __('messages.notes') }}</label><textarea class="form-control"
                        id="notes" name="notes" rows="2">{{ old('notes') }}</textarea></div>
                <button type="submit" class="btn btn-primary">{{ __('messages.create') }}</button>
                <a href="{{ route('assets.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection