@extends('layouts.adminlte')

@section('title', 'Create Review')
@section('page_title', 'Create Review')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.reviews.index') }}">Reviews</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Performance Review</h3></div>
    <div class="card-body">
        <form action="{{ route('performance.reviews.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="employee_id">Employee <span class="text-danger">*</span></label>
                <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id', request('employee_id')) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} ({{ $emp->employee_id ?? $emp->id }})</option>
                    @endforeach
                </select>
                @error('employee_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="cycle_id">Review Cycle <span class="text-danger">*</span></label>
                <select class="form-control @error('cycle_id') is-invalid @enderror" id="cycle_id" name="cycle_id" required>
                    @foreach($cycles as $c)
                        <option value="{{ $c->id }}" {{ old('cycle_id', request('cycle_id')) == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->period_start->format('M Y') }} – {{ $c->period_end->format('M Y') }})</option>
                    @endforeach
                </select>
                @error('cycle_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="reviewer_id">Reviewer (Manager)</label>
                <select class="form-control @error('reviewer_id') is-invalid @enderror" id="reviewer_id" name="reviewer_id">
                    <option value="">— Auto (employee’s manager) —</option>
                    @foreach($reviewers as $u)
                        <option value="{{ $u->id }}" {{ old('reviewer_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                @error('reviewer_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <small class="text-muted">Leave empty to use the employee’s manager as reviewer.</small>
            </div>
            <button type="submit" class="btn btn-primary">Create Review</button>
            <a href="{{ route('performance.reviews.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
