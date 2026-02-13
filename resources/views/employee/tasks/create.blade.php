@extends('layouts.adminlte')

@section('title', __('messages.create_task'))
@section('page_title', __('messages.create_task'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employee-tasks.index') }}">{{ __('messages.employee_tasks') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.new_task') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('employee-tasks.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title') }}" required maxlength="255">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">{{ __('messages.description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3" maxlength="2000">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employee_id">{{ __('messages.assign_to_employee') }}</label>
                            <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id"
                                name="employee_id">
                                <option value="">— {{ __('messages.all') }} / {{ __('messages.unassigned') }} —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->full_name }} ({{ $emp->employee_id ?? $emp->id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">{{ __('messages.due_date_help') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="due_date">{{ __('messages.due_date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date"
                                name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required>
                            @error('due_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="priority">{{ __('messages.priority') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('priority') is-invalid @enderror" id="priority"
                                name="priority" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('messages.low') }}
                                </option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>
                                    {{ __('messages.medium') }}</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                    {{ __('messages.high') }}</option>
                            </select>
                            @error('priority')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                    {{ __('messages.pending') }}</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                                    {{ __('messages.in_progress') }}</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                    {{ __('messages.completed') }}</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="action_route">{{ __('messages.action_route') }}</label>
                            <input type="text" class="form-control @error('action_route') is-invalid @enderror"
                                id="action_route" name="action_route" value="{{ old('action_route') }}"
                                placeholder="{{ __('messages.placeholder_task_url') }}" maxlength="100">
                            @error('action_route')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">{{ __('messages.action_route_help') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="action_label">{{ __('messages.action_label') }}</label>
                            <input type="text" class="form-control @error('action_label') is-invalid @enderror"
                                id="action_label" name="action_label" value="{{ old('action_label') }}"
                                placeholder="{{ __('messages.placeholder_task_title') }}" maxlength="50">
                            @error('action_label')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('messages.create_task') }}</button>
                    <a href="{{ route('employee-tasks.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection