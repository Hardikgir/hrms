@extends('layouts.adminlte')

@section('title', 'Create Goal')
@section('page_title', 'Create Goal')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.goals.index') }}">Goals</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">New Goal (KRA/OKR)</h3></div>
    <div class="card-body">
        <form action="{{ route('performance.goals.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employee_id">Employee <span class="text-danger">*</span></label>
                        <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} ({{ $emp->employee_id ?? $emp->id }})</option>
                            @endforeach
                        </select>
                        @error('employee_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cycle_id">Review Cycle</label>
                        <select class="form-control @error('cycle_id') is-invalid @enderror" id="cycle_id" name="cycle_id">
                            <option value="">— None —</option>
                            @foreach($cycles as $c)
                                <option value="{{ $c->id }}" {{ old('cycle_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('cycle_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="type">Type <span class="text-danger">*</span></label>
                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="kra" {{ old('type', 'kra') == 'kra' ? 'selected' : '' }}>KRA</option>
                    <option value="okr" {{ old('type') == 'okr' ? 'selected' : '' }}>OKR</option>
                </select>
                @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required maxlength="255">
                @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="target_value">Target value</label>
                        <input type="text" class="form-control @error('target_value') is-invalid @enderror" id="target_value" name="target_value" value="{{ old('target_value') }}" maxlength="100">
                        @error('target_value')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="target_unit">Unit</label>
                        <input type="text" class="form-control @error('target_unit') is-invalid @enderror" id="target_unit" name="target_unit" value="{{ old('target_unit') }}" maxlength="50" placeholder="{{ __('messages.placeholder_target_unit') }}">
                        @error('target_unit')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="weight">Weight (1–100)</label>
                        <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', 100) }}" min="1" max="100">
                        @error('weight')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="achieved" {{ old('status') == 'achieved' ? 'selected' : '' }}>Achieved</option>
                            <option value="not_achieved" {{ old('status') == 'not_achieved' ? 'selected' : '' }}>Not Achieved</option>
                        </select>
                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="due_date">Due date</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Goal</button>
            <a href="{{ route('performance.goals.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
