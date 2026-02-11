@extends('layouts.ess')

@section('title', 'Edit Leave Request')
@section('page_title', 'Edit Leave Request')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.leaves') }}">Leaves</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.leaves.show', $leave) }}">Details</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Leave Request</h3>
                <div class="card-tools">
                    <a href="{{ route('ess.leaves.show', $leave) }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</a>
                </div>
            </div>
            <form action="{{ route('ess.leaves.update', $leave) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if($employees->count() > 1)
                    <div class="form-group">
                        <label for="employee_id">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id', $leave->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                        @error('employee_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    @else
                        <input type="hidden" name="employee_id" value="{{ $leave->employee_id }}">
                    @endif

                    <div class="form-group">
                        <label for="leave_type_id">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type_id" id="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror" required>
                            @foreach($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}" {{ old('leave_type_id', $leave->leave_type_id) == $leaveType->id ? 'selected' : '' }}>{{ $leaveType->name }}</option>
                            @endforeach
                        </select>
                        @error('leave_type_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') : '') }}" required>
                                @error('start_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') : '') }}" required>
                                @error('end_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason', $leave->reason) }}</textarea>
                        @error('reason')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Leave Request</button>
                    <a href="{{ route('ess.leaves.show', $leave) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header"><h3 class="card-title">Leave Information</h3></div>
            <div class="card-body">
                <p><strong>Status:</strong>
                    @if($leave->status === 'pending')<span class="badge badge-warning">Pending</span>
                    @elseif($leave->status === 'approved')<span class="badge badge-success">Approved</span>
                    @else<span class="badge badge-danger">Rejected</span>
                    @endif
                </p>
                <p><strong>{{ __('messages.total_days') }}:</strong> {{ $leave->total_days }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
