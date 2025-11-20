@extends('layouts.adminlte')

@section('title', 'Edit Leave Request')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Leave Request</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leaves</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Leave Request</h3>
                    </div>
                    <form action="{{ route('leaves.update', $leave) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" 
                                                {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="leave_type_id">Leave Type <span class="text-danger">*</span></label>
                                <select name="leave_type_id" id="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $leaveType)
                                        <option value="{{ $leaveType->id }}" 
                                                {{ old('leave_type_id', $leave->leave_type_id) == $leaveType->id ? 'selected' : '' }}>
                                            {{ $leaveType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" id="start_date" 
                                               class="form-control @error('start_date') is-invalid @enderror" 
                                               value="{{ old('start_date', $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') : '') }}" required>
                                        @error('start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date" id="end_date" 
                                               class="form-control @error('end_date') is-invalid @enderror" 
                                               value="{{ old('end_date', $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') : '') }}" required>
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="reason">Reason <span class="text-danger">*</span></label>
                                <textarea name="reason" id="reason" rows="4" 
                                          class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason', $leave->reason) }}</textarea>
                                @error('reason')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Leave Request
                            </button>
                            <a href="{{ route('leaves.show', $leave) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Leave Information</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Status:</strong> 
                            @if($leave->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($leave->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </p>
                        <p><strong>Total Days:</strong> {{ $leave->total_days }}</p>
                        @if($leave->approved_at)
                            <p><strong>Approved At:</strong> {{ \Carbon\Carbon::parse($leave->approved_at)->format('d M Y H:i') }}</p>
                        @endif
                        @if($leave->rejected_at)
                            <p><strong>Rejected At:</strong> {{ \Carbon\Carbon::parse($leave->rejected_at)->format('d M Y H:i') }}</p>
                            @if($leave->rejection_reason)
                                <p><strong>Rejection Reason:</strong> {{ $leave->rejection_reason }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Calculate total days when dates change
        $('#start_date, #end_date').on('change', function() {
            var startDate = new Date($('#start_date').val());
            var endDate = new Date($('#end_date').val());
            
            if (startDate && endDate && endDate >= startDate) {
                var diffTime = Math.abs(endDate - startDate);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                // You can display this in a label if needed
            }
        });
    });
</script>
@endpush

