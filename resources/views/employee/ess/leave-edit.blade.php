@extends('layouts.ess')

@section('title', 'Edit Leave Request')
@section('page_title', 'Edit Leave Request')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.leaves') }}">Leaves</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.leaves.show', $leave) }}">Details</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Leave Request</h3>
                <div class="card-tools">
                    <a href="{{ route('ess.leaves.show', $leave) }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</a>
                </div>
            </div>
            <div class="card-body p-0">
                @livewire('leave.leave-application-form', ['leave' => $leave])
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header"><h3 class="card-title">Leave Information</h3></div>
            <div class="card-body">
                <p><strong>Status:</strong>
                    @if($leave->status === 'pending')<span class="badge badge-warning">Pending</span>
                    @elseif($leave->status === 'approved')<span class="badge badge-success">Approved</span>
                    @else<span class="badge badge-danger">Rejected</span>
                    @endif
                </p>
                <p><strong>{{ __('messages.total_days') }}:</strong> {{ $leave->total_days }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
