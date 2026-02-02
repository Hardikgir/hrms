@extends('layouts.adminlte')
@section('title', 'Edit Asset')
@section('page_title', 'Edit Asset')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Assets</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Asset</h3></div>
    <div class="card-body">
        <form action="{{ route('assets.update', $asset) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group"><label for="name">Name *</label><input type="text" class="form-control" id="name" name="name" value="{{ old('name', $asset->name) }}" required></div>
            <div class="form-group"><label for="type">Type *</label><input type="text" class="form-control" id="type" name="type" value="{{ old('type', $asset->type) }}" required></div>
            <div class="row"><div class="col-md-6"><div class="form-group"><label for="serial_number">Serial Number</label><input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"></div></div><div class="col-md-6"><div class="form-group"><label for="asset_tag">Asset Tag</label><input type="text" class="form-control" id="asset_tag" name="asset_tag" value="{{ old('asset_tag', $asset->asset_tag) }}"></div></div></div>
            <div class="form-group"><label for="employee_id">Assigned to</label><select class="form-control" id="employee_id" name="employee_id"><option value="">— None —</option>@foreach($employees as $emp)<option value="{{ $emp->id }}" {{ old('employee_id', $asset->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>@endforeach</select></div>
            <div class="form-group"><label for="status">Status *</label><select class="form-control" id="status" name="status" required><option value="available" {{ old('status', $asset->status) == 'available' ? 'selected' : '' }}>Available</option><option value="assigned" {{ old('status', $asset->status) == 'assigned' ? 'selected' : '' }}>Assigned</option><option value="under_maintenance" {{ old('status', $asset->status) == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option><option value="retired" {{ old('status', $asset->status) == 'retired' ? 'selected' : '' }}>Retired</option></select></div>
            <div class="row"><div class="col-md-6"><div class="form-group"><label for="purchase_date">Purchase Date</label><input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}"></div></div><div class="col-md-6"><div class="form-group"><label for="purchase_value">Purchase Value</label><input type="number" step="0.01" class="form-control" id="purchase_value" name="purchase_value" value="{{ old('purchase_value', $asset->purchase_value) }}"></div></div></div>
            <div class="form-group"><label for="location">Location</label><input type="text" class="form-control" id="location" name="location" value="{{ old('location', $asset->location) }}"></div>
            <div class="form-group"><label for="notes">Notes</label><textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $asset->notes) }}</textarea></div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('assets.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
