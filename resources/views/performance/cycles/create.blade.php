@extends('layouts.adminlte')

@section('title', 'Create Review Cycle')
@section('page_title', 'Create Review Cycle')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.cycles.index') }}">Review Cycles</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Review Cycle</h3></div>
    <div class="card-body">
        <form action="{{ route('performance.cycles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="period_start">Period Start <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('period_start') is-invalid @enderror" id="period_start" name="period_start" value="{{ old('period_start') }}" required>
                        @error('period_start')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="period_end">Period End <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('period_end') is-invalid @enderror" id="period_end" name="period_end" value="{{ old('period_end') }}" required>
                        @error('period_end')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Cycle</button>
            <a href="{{ route('performance.cycles.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
