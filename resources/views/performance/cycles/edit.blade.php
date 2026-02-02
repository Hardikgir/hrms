@extends('layouts.adminlte')

@section('title', 'Edit Review Cycle')
@section('page_title', 'Edit Review Cycle')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.cycles.index') }}">Review Cycles</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.cycles.show', $cycle) }}">{{ $cycle->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Cycle</h3></div>
    <div class="card-body">
        <form action="{{ route('performance.cycles.update', $cycle) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $cycle->name) }}" required maxlength="255">
                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="period_start">Period Start <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('period_start') is-invalid @enderror" id="period_start" name="period_start" value="{{ old('period_start', $cycle->period_start->format('Y-m-d')) }}" required>
                        @error('period_start')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="period_end">Period End <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('period_end') is-invalid @enderror" id="period_end" name="period_end" value="{{ old('period_end', $cycle->period_end->format('Y-m-d')) }}" required>
                        @error('period_end')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            @if(!$cycle->isClosed())
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                    <option value="draft" {{ old('status', $cycle->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ old('status', $cycle->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="closed" {{ old('status', $cycle->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            @endif
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('performance.cycles.show', $cycle) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
