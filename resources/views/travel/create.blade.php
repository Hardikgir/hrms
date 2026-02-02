@extends('layouts.adminlte')
@section('title', 'New Travel Request')
@section('page_title', 'New Travel Request')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('travel.index') }}">Travel</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Travel Request</h3></div>
    <div class="card-body">
        <form action="{{ route('travel.store') }}" method="POST">
            @csrf
            <div class="form-group"><label for="purpose">Purpose *</label><input type="text" class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" value="{{ old('purpose') }}" required>@error('purpose')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label for="destination">Destination</label><input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination') }}"></div>
            <div class="row"><div class="col-md-6"><div class="form-group"><label for="start_date">Start Date *</label><input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required></div></div><div class="col-md-6"><div class="form-group"><label for="end_date">End Date *</label><input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required></div></div></div>
            <div class="form-group"><label for="estimated_amount">Estimated Amount</label><input type="number" step="0.01" class="form-control" id="estimated_amount" name="estimated_amount" value="{{ old('estimated_amount') }}"></div>
            <div class="form-group"><label for="notes">Notes</label><textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea></div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('travel.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
