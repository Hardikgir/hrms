@extends('layouts.ess')

@section('title', 'My Leaves')
@section('page_title', 'My Leaves')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Leaves</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        @livewire('leave.leave-balance-dashboard')

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">My Leave Requests</h3>
                <div class="card-tools">
                    <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Apply for Leave
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('ess.leaves') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>

                @if($leaves->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                    <td>{{ $leave->total_days }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($leave->reason, 50) }}</td>
                                    <td>
                                        @if($leave->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($leave->status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($leave->status === 'pending')
                                            <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No leave requests found.</p>
                @endif
            </div>
            <div class="card-footer">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

