@extends('layouts.adminlte')
@section('title', 'Travel Requests')
@section('page_title', 'Travel Requests')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Travel</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Travel Requests</h3>
        <div class="card-tools">
            @if(auth()->user()->employee)
            <a href="{{ route('travel.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Request</a>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($employees->isNotEmpty())
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3"><select name="employee_id" class="form-control"><option value="">All</option>@foreach($employees as $emp)<option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="status" class="form-control"><option value="">All</option><option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option><option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option><option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option><option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option></select></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">Filter</button></div>
            </div>
        </form>
        @endif
        <table class="table table-bordered table-striped">
            <thead><tr><th>Employee</th><th>Purpose</th><th>Dates</th><th>Estimated</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($requests as $r)
                <tr>
                    <td>{{ $r->employee->full_name ?? '-' }}</td>
                    <td>{{ Str::limit($r->purpose, 30) }}</td>
                    <td>{{ $r->start_date->format('d M') }} – {{ $r->end_date->format('d M Y') }}</td>
                    <td>{{ $r->estimated_amount ? number_format($r->estimated_amount, 2) : '-' }}</td>
                    <td>@if($r->status === 'pending')<span class="badge badge-warning">Pending</span>@elseif($r->status === 'approved')<span class="badge badge-info">Approved</span>@elseif($r->status === 'rejected')<span class="badge badge-danger">Rejected</span>@else<span class="badge badge-success">Completed</span>@endif</td>
                    <td>
                        <a href="{{ route('travel.show', $r) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        @can('approve', $r)
                        @if($r->status === 'pending')
                        <form action="{{ route('travel.approve', $r) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-success">Approve</button></form>
                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $r->id }}">Reject</button>
                        @endif
                        @if($r->status === 'approved')
                        <form action="{{ route('travel.complete', $r) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-primary">Mark Completed</button></form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No travel requests.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $requests->links() }}
    </div>
</div>
@foreach($requests->where('status', 'pending') as $r)
@can('approve', $r)
<div class="modal fade" id="rejectModal{{ $r->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('travel.reject', $r) }}" method="POST">@csrf
            <div class="modal-header"><h5 class="modal-title">Reject Request</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><div class="form-group"><label for="reason{{ $r->id }}">Reason *</label><textarea class="form-control" id="reason{{ $r->id }}" name="reason" rows="3" required></textarea></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Reject</button></div>
        </form>
    </div></div>
</div>
@endcan
@endforeach
@endsection
