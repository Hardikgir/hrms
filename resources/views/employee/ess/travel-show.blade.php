@extends('layouts.ess')

@section('title', 'Travel Request')
@section('page_title', 'Travel Request')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.travel') }}">Travel</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Travel Request #{{ $travel->id }}</h3>
        <div class="card-tools">
            <a href="{{ route('ess.travel') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Back to Travel</a>
        </div>
    </div>
    <div class="card-body">
        <p><strong>Purpose:</strong> {{ $travel->purpose }}</p>
        <p><strong>Destination:</strong> {{ $travel->destination ?? '-' }}</p>
        <p><strong>Dates:</strong> {{ $travel->start_date->format('d M Y') }} – {{ $travel->end_date->format('d M Y') }}</p>
        <p><strong>Estimated:</strong> {{ $travel->estimated_amount ? number_format($travel->estimated_amount, 2) : '-' }}</p>
        <p><strong>Actual:</strong> {{ $travel->actual_amount ? number_format($travel->actual_amount, 2) : '-' }}</p>
        <p><strong>Status:</strong>
            @if($travel->status === 'pending')<span class="badge badge-warning">Pending</span>
            @elseif($travel->status === 'approved')<span class="badge badge-info">Approved</span>
            @elseif($travel->status === 'rejected')<span class="badge badge-danger">Rejected</span>
            @else<span class="badge badge-success">Completed</span>
            @endif
        </p>
        @if($travel->rejection_reason)<p><strong>Rejection reason:</strong> {{ $travel->rejection_reason }}</p>@endif
        @if($travel->notes)<p><strong>Notes:</strong> {{ $travel->notes }}</p>@endif
    </div>
</div>
@endsection
