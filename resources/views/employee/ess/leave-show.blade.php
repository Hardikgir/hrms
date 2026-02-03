@extends('layouts.ess')

@section('title', 'Leave Details')
@section('page_title', 'Leave Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.leaves') }}">Leaves</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Leave Request Details</h3>
        <div class="card-tools">
            <a href="{{ route('ess.leaves') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Back to Leaves</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="25%">Leave Type</th>
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

        @if($leave->status === 'pending')
            <div class="mt-3">
                <a href="{{ route('ess.leaves.edit', $leave) }}" class="btn btn-primary">Edit Leave</a>
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('ess.leaves') }}" class="btn btn-secondary">Back to My Leaves</a>
        </div>
    </div>
</div>
@endsection
