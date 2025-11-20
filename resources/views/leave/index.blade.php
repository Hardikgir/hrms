@extends('layouts.adminlte')

@section('title', 'Leaves')
@section('page_title', 'Leave Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Leaves</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Leave Requests</h3>
        <div class="card-tools">
            @can('create leaves')
            <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Apply Leave
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('leaves.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <td>{{ $leave->employee->full_name ?? '-' }}</td>
                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                    <td>{{ $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('d M Y') : '-' }}</td>
                    <td>{{ $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('d M Y') : '-' }}</td>
                    <td>{{ $leave->total_days ?? 0 }}</td>
                    <td>
                        <span class="badge badge-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                    <td>
                        @can('view leaves')
                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endcan
                        @can('update leaves')
                        <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No leave requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection

