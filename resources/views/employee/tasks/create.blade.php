@extends('layouts.adminlte')

@section('title', 'Create Task')
@section('page_title', 'Create Task')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employee-tasks.index') }}">Employee Tasks</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">New Task</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('employee-tasks.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required maxlength="255">
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" maxlength="2000">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="employee_id">Assign to Employee</label>
                        <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id">
                            <option value="">— All / Unassigned —</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->full_name }} ({{ $emp->employee_id ?? $emp->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Leave empty for company-wide / template task.</small>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required>
                        @error('due_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority <span class="text-danger">*</span></label>
                        <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="action_route">Action route (ESS link)</label>
                        <input type="text" class="form-control @error('action_route') is-invalid @enderror" id="action_route" name="action_route" value="{{ old('action_route') }}" placeholder="e.g. ess/onboarding-documents" maxlength="100">
                        @error('action_route')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Use route name or path, e.g. <code>ess.onboarding-documents</code> or full URL.</small>
                    </div>
                    <div class="form-group">
                        <label for="action_label">Action button label</label>
                        <input type="text" class="form-control @error('action_label') is-invalid @enderror" id="action_label" name="action_label" value="{{ old('action_label') }}" placeholder="e.g. Submit documents" maxlength="50">
                        @error('action_label')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create Task</button>
                <a href="{{ route('employee-tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
