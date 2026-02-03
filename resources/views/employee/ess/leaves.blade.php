@extends('layouts.ess')

@section('title', 'My Leaves')
@section('page_title', 'My Leaves')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Leaves</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        {{-- Leave balance --}}
        <div class="card card-outline card-primary mb-4">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Leave Balance</h3>
            </div>
            <div class="card-body">
                @livewire('leave.leave-balance-dashboard')
            </div>
        </div>

        {{-- Quick apply + stats --}}
        <div class="row mb-3">
            <div class="col-md-8">
                <a href="{{ route('ess.leaves.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Apply for Leave
                </a>
                <p class="text-muted small mt-2 mb-0">Submit a new leave request. You can edit or cancel pending requests.</p>
            </div>
            @if($leaves->total() > 0)
                <div class="col-md-4 text-right">
                    <span class="badge badge-warning mr-1">Pending: {{ $pendingCount ?? 0 }}</span>
                    <span class="badge badge-success mr-1">Approved: {{ $approvedCount ?? 0 }}</span>
                    <span class="badge badge-danger">Rejected: {{ $rejectedCount ?? 0 }}</span>
                </div>
            @endif
        </div>

        {{-- Leave requests --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Leave Requests</h3>
                <div class="card-tools">
                    <form method="GET" action="{{ route('ess.leaves') }}" class="form-inline">
                        <label class="mr-2 mb-0 small text-muted">Status</label>
                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @if($leaves->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Period</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $leave)
                                    <tr>
                                        <td><strong>{{ $leave->leaveType->name ?? 'N/A' }}</strong></td>
                                        <td>
                                            {{ $leave->start_date->format('d M Y') }} – {{ $leave->end_date->format('d M Y') }}
                                        </td>
                                        <td>{{ $leave->total_days }} day{{ $leave->total_days !== 1 ? 's' : '' }}</td>
                                        <td title="{{ $leave->reason }}">{{ str()->limit($leave->reason, 40) }}</td>
                                        <td>
                                            @if($leave->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($leave->status === 'rejected')
                                                <span class="badge badge-danger" title="{{ $leave->rejection_reason ?? '' }}">Rejected</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('ess.leaves.show', $leave) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                                            @if($leave->status === 'pending')
                                                <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No leave requests</h5>
                        <p class="text-muted mb-3">You haven't applied for any leave yet.</p>
                        <a href="{{ route('ess.leaves.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Apply for Leave</a>
                    </div>
                @endif
            </div>
            @if($leaves->hasPages())
                <div class="card-footer d-flex justify-content-center">
                    {{ $leaves->links('vendor.pagination.bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
