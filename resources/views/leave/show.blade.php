@extends('layouts.adminlte')

@section('title', 'Leave Details')
@section('page_title', 'Leave Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leaves</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Leave Request Details</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Employee</th>
                <td>{{ $leave->employee->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Leave Type</th>
                <td>{{ $leave->leaveType->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td>{{ $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <th>End Date</th>
                <td>{{ $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Total Days</th>
                <td>{{ $leave->total_days }}</td>
            </tr>
            <tr>
                <th>Reason</th>
                <td>{{ $leave->reason }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($leave->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @elseif($leave->status === 'hr_approved')
                        <span class="badge badge-info">HR Approved</span>
                    @elseif($leave->status === 'rejected')
                        <span class="badge badge-danger">Rejected</span>
                    @elseif($leave->status === 'cancelled')
                        <span class="badge badge-secondary">Cancelled</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </td>
            </tr>
            @if($leave->hr_approved_at)
            <tr>
                <th>HR Approved By</th>
                <td>{{ $leave->hrApprovedBy->name ?? '-' }} on {{ $leave->hr_approved_at->format('d M Y H:i') }}</td>
            </tr>
            @endif
            @if($leave->approved_at)
            <tr>
                <th>Final Approved By</th>
                <td>{{ $leave->approvedBy->name ?? '-' }} on {{ $leave->approved_at->format('d M Y H:i') }}</td>
            </tr>
            @endif
            @if($leave->rejected_at)
            <tr>
                <th>Rejected By</th>
                <td>{{ $leave->rejectedBy->name ?? '-' }} on {{ $leave->rejected_at->format('d M Y H:i') }}</td>
            </tr>
            @endif
        </table>

        <div class="mt-3">
            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection

