@extends('layouts.adminlte')
@section('title', 'New Asset')
@section('page_title', 'New Asset')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Assets</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Asset</h3></div>
    <div class="card-body">
        <form action="{{ route('assets.store') }}" method="POST">
            @csrf
            <div class="form-group"><label for="name">Name *</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>@error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div class="form-group">
                <label for="type">Type *</label>
                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="">Select type</option>
                    @foreach($assetTypes as $t)
                        <option value="{{ $t->slug }}" {{ old('type') === $t->slug ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
                @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                @if($assetTypes->isEmpty())
                    <small class="text-muted">No asset types. <a href="{{ route('asset-types.create') }}">Add one</a> first.</small>
                @endif
            </div>
            <div class="row"><div class="col-md-6"><div class="form-group"><label for="serial_number">Serial Number</label><input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number') }}"></div></div><div class="col-md-6"><div class="form-group"><label for="asset_tag">Asset Tag</label><input type="text" class="form-control" id="asset_tag" name="asset_tag" value="{{ old('asset_tag') }}"></div></div></div>
            <div class="row"><div class="col-md-6"><div class="form-group"><label for="purchase_date">Purchase Date</label><input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}"></div></div><div class="col-md-6"><div class="form-group"><label for="purchase_value">Purchase Value</label><input type="number" step="0.01" class="form-control" id="purchase_value" name="purchase_value" value="{{ old('purchase_value') }}"></div></div></div>
            <div class="form-group"><label for="location">Location</label><input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}"></div>
            <div class="form-group"><label for="notes">Notes</label><textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea></div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('assets.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
