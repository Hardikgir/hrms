@extends('layouts.ess')
@section('title', 'My Travel')
@section('page_title', 'My Travel Requests')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Travel</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Travel Requests</h3>
        <div class="card-tools">
            <a href="{{ route('ess.travel.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Request</a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($requests->total() > 0)
        <table class="table table-striped mb-0">
            <thead><tr><th>Purpose</th><th>Dates</th><th>Estimated</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($requests as $r)
                <tr>
                    <td>{{ Str::limit($r->purpose, 35) }}</td>
                    <td>{{ $r->start_date->format('d M') }} – {{ $r->end_date->format('d M Y') }}</td>
                    <td>{{ $r->estimated_amount ? number_format($r->estimated_amount, 2) : '-' }}</td>
                    <td>@if($r->status === 'pending')<span class="badge badge-warning">Pending</span>@elseif($r->status === 'approved')<span class="badge badge-info">Approved</span>@elseif($r->status === 'rejected')<span class="badge badge-danger">Rejected</span>@else<span class="badge badge-success">Completed</span>@endif</td>
                    <td><a href="{{ route('ess.travel.show', $r) }}" class="btn btn-sm btn-info">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $requests->links() }}</div>
        @else
        <div class="p-4 text-center text-muted">No travel requests. <a href="{{ route('ess.travel.create') }}">Submit a request</a>.</div>
        @endif
    </div>
</div>
@endsection
