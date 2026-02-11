@extends('layouts.adminlte')
@section('title', 'Travel Request')
@section('page_title', 'Travel Request')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('travel.index') }}">Travel</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Travel Request #{{ $travel->id }}</h3>
        <div class="card-tools">
            @can('approve', $travel)
            @if($travel->status === 'pending')
            <form action="{{ route('travel.approve', $travel) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-success">Approve</button></form>
            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal">Reject</button>
            @endif
            @if($travel->status === 'approved')
            <form action="{{ route('travel.complete', $travel) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-primary">Mark Completed</button></form>
            @endif
            @endcan
        </div>
    </div>
    <div class="card-body">
        <p><strong>Employee:</strong> {{ $travel->employee->full_name ?? '-' }}</p>
        <p><strong>{{ __('messages.purpose') }}:</strong> {{ $travel->purpose }}</p>
        <p><strong>{{ __('messages.destination') }}:</strong> {{ $travel->destination ?? '-' }}</p>
        <p><strong>{{ __('messages.dates_label') }}:</strong> {{ $travel->start_date->format('d M Y') }} – {{ $travel->end_date->format('d M Y') }}</p>
        <p><strong>Estimated:</strong> {{ $travel->estimated_amount ? number_format($travel->estimated_amount, 2) : '-' }}</p>
        <p><strong>Actual:</strong> {{ $travel->actual_amount ? number_format($travel->actual_amount, 2) : '-' }}</p>
        <p><strong>Status:</strong> @if($travel->status === 'pending')<span class="badge badge-warning">Pending</span>@elseif($travel->status === 'approved')<span class="badge badge-info">Approved</span>@elseif($travel->status === 'rejected')<span class="badge badge-danger">Rejected</span>@else<span class="badge badge-success">Completed</span>@endif</p>
        @if($travel->rejection_reason)<p><strong>Rejection reason:</strong> {{ $travel->rejection_reason }}</p>@endif
        @if($travel->notes)<p><strong>Notes:</strong> {{ $travel->notes }}</p>@endif
    </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('travel.reject', $travel) }}" method="POST">@csrf
            <div class="modal-header"><h5 class="modal-title">Reject Request</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><div class="form-group"><label for="reason">Reason *</label><textarea class="form-control" id="reason" name="reason" rows="3" required></textarea></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Reject</button></div>
        </form>
    </div></div>
</div>
<a href="{{ route('travel.index') }}" class="btn btn-secondary mt-2">Back</a>
@endsection
