@extends('layouts.adminlte')
@section('title', __('messages.edit_asset'))
@section('page_title', __('messages.edit_asset'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">{{ __('messages.assets') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.edit_asset') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('assets.update', $asset) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group"><label for="name">{{ __('messages.name') }} *</label><input type="text"
                        class="form-control" id="name" name="name" value="{{ old('name', $asset->name) }}" required></div>
                <div class="form-group">
                    <label for="type">{{ __('messages.type') }} *</label>
                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">{{ __('messages.select_type') }}</option>
                        @foreach($assetTypes as $t)
                            <option value="{{ $t->slug }}" {{ old('type', $asset->type) === $t->slug ? 'selected' : '' }}>
                                {{ $t->name }}</option>
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
                                value="{{ old('serial_number', $asset->serial_number) }}"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label for="asset_tag">{{ __('messages.asset_tag') }}</label><input
                                type="text" class="form-control" id="asset_tag" name="asset_tag"
                                value="{{ old('asset_tag', $asset->asset_tag) }}"></div>
                    </div>
                </div>
                <div class="form-group"><label for="employee_id">{{ __('messages.assigned_to') }}</label><select
                        class="form-control" id="employee_id" name="employee_id">
                        <option value="">— {{ __('messages.none') }} —</option>@foreach($employees as $emp)<option
                        value="{{ $emp->id }}" {{ old('employee_id', $asset->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>@endforeach
                    </select></div>
                <div class="form-group"><label for="status">{{ __('messages.status') }} *</label><select
                        class="form-control" id="status" name="status" required>
                        <option value="available" {{ old('status', $asset->status) == 'available' ? 'selected' : '' }}>
                            {{ __('messages.available') }}</option>
                        <option value="assigned" {{ old('status', $asset->status) == 'assigned' ? 'selected' : '' }}>
                            {{ __('messages.assigned') }}</option>
                        <option value="under_maintenance" {{ old('status', $asset->status) == 'under_maintenance' ? 'selected' : '' }}>{{ __('messages.under_maintenance') }}</option>
                        <option value="retired" {{ old('status', $asset->status) == 'retired' ? 'selected' : '' }}>
                            {{ __('messages.retired') }}</option>
                    </select></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="purchase_date">{{ __('messages.purchase_date') }}</label><input
                                type="date" class="form-control" id="purchase_date" name="purchase_date"
                                value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label
                                for="purchase_value">{{ __('messages.purchase_value') }}</label><input type="number"
                                step="0.01" class="form-control" id="purchase_value" name="purchase_value"
                                value="{{ old('purchase_value', $asset->purchase_value) }}"></div>
                    </div>
                </div>
                <div class="form-group"><label for="location">{{ __('messages.location') }}</label><input type="text"
                        class="form-control" id="location" name="location" value="{{ old('location', $asset->location) }}">
                </div>
                <div class="form-group"><label for="notes">{{ __('messages.notes') }}</label><textarea class="form-control"
                        id="notes" name="notes" rows="2">{{ old('notes', $asset->notes) }}</textarea></div>
                <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                <a href="{{ route('assets.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </form>
        </div>
    </div>
@endsection