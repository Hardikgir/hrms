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
                    <span class="badge badge-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($leave->status) }}
                    </span>
                </td>
            </tr>
        </table>

        <div class="mt-3">
            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection

