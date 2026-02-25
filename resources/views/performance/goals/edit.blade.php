@extends('layouts.adminlte')

@section('title', 'Edit Goal')
@section('page_title', 'Edit Goal')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('performance.goals.index') }}">Goals</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Goal</h3></div>
    <div class="card-body">
        <form action="{{ route('performance.goals.update', $goal) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employee_id">Employee <span class="text-danger">*</span></label>
                        <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id', $goal->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
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
                                <option value="{{ $c->id }}" {{ old('cycle_id', $goal->cycle_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('cycle_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="type">Type <span class="text-danger">*</span></label>
                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="kra" {{ old('type', $goal->type) == 'kra' ? 'selected' : '' }}>KRA</option>
                    <option value="okr" {{ old('type', $goal->type) == 'okr' ? 'selected' : '' }}>OKR</option>
                </select>
                @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $goal->title) }}" required maxlength="255">
                @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $goal->description) }}</textarea>
                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="target_value">Target value</label>
                        <input type="text" class="form-control @error('target_value') is-invalid @enderror" id="target_value" name="target_value" value="{{ old('target_value', $goal->target_value) }}" maxlength="100">
                        @error('target_value')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="target_unit">Unit</label>
                        <input type="text" class="form-control @error('target_unit') is-invalid @enderror" id="target_unit" name="target_unit" value="{{ old('target_unit', $goal->target_unit) }}" maxlength="50">
                        @error('target_unit')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="weight">Weight (1–100)</label>
                        <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $goal->weight) }}" min="1" max="100">
                        @error('weight')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="active" {{ old('status', $goal->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ old('status', $goal->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="achieved" {{ old('status', $goal->status) == 'achieved' ? 'selected' : '' }}>Achieved</option>
                            <option value="not_achieved" {{ old('status', $goal->status) == 'not_achieved' ? 'selected' : '' }}>Not Achieved</option>
                        </select>
                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="due_date">Due date</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $goal->due_date?->format('Y-m-d')) }}">
                        @error('due_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="achieved_value">Achieved value</label>
                        <input type="text" class="form-control @error('achieved_value') is-invalid @enderror" id="achieved_value" name="achieved_value" value="{{ old('achieved_value', $goal->achieved_value) }}" maxlength="100">
                        @error('achieved_value')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Goal</button>
            <a href="{{ route('performance.goals.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
