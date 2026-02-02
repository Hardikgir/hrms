@extends('layouts.adminlte')
@section('title', 'Submit Resignation')
@section('page_title', 'Submit Resignation')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('exit.index') }}">Exit</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Submit Resignation / Exit Request</h3></div>
    <div class="card-body">
        <form action="{{ route('exit.store') }}" method="POST">
            @csrf
            <div class="form-group"><label for="resignation_date">Resignation Date *</label><input type="date" class="form-control @error('resignation_date') is-invalid @enderror" id="resignation_date" name="resignation_date" value="{{ old('resignation_date') }}" required>@error('resignation_date')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label for="last_working_date">Last Working Date *</label><input type="date" class="form-control @error('last_working_date') is-invalid @enderror" id="last_working_date" name="last_working_date" value="{{ old('last_working_date') }}" required>@error('last_working_date')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label for="reason">Reason</label><select class="form-control" id="reason" name="reason"><option value="">Select</option><option value="voluntary" {{ old('reason') == 'voluntary' ? 'selected' : '' }}>Voluntary</option><option value="retirement" {{ old('reason') == 'retirement' ? 'selected' : '' }}>Retirement</option><option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Other</option></select></div>
            <div class="form-group"><label for="reason_details">Reason Details</label><textarea class="form-control" id="reason_details" name="reason_details" rows="3">{{ old('reason_details') }}</textarea></div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('exit.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
