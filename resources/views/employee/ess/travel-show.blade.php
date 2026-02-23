@extends('layouts.ess')

@section('title', __('messages.travel_requests'))
@section('page_title', __('messages.travel_requests'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.travel') }}">{{ __('messages.travel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.view') }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.travel_requests') }} #{{ $travel->id }}</h3>
        <div class="card-tools">
            <a href="{{ route('ess.travel') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('messages.back_to_travel') }}</a>
        </div>
    </div>
    <div class="card-body">
        <p><strong>{{ __('messages.purpose') }}:</strong> {{ $travel->purpose }}</p>
        <p><strong>{{ __('messages.destination') }}:</strong> {{ $travel->destination ?? '-' }}</p>
        <p><strong>{{ __('messages.dates_label') }}:</strong> {{ $travel->start_date->format('d M Y') }} – {{ $travel->end_date->format('d M Y') }}</p>
        <p><strong>{{ __('messages.estimated') }}:</strong> {{ $travel->estimated_amount ? number_format($travel->estimated_amount, 2) : '-' }}</p>
        <p><strong>{{ __('messages.actual') }}:</strong> {{ $travel->actual_amount ? number_format($travel->actual_amount, 2) : '-' }}</p>
        <p><strong>{{ __('messages.status') }}:</strong>
            @if($travel->status === 'pending')<span class="badge badge-warning">{{ __('messages.pending') }}</span>
            @elseif($travel->status === 'approved')<span class="badge badge-info">{{ __('messages.approved') }}</span>
            @elseif($travel->status === 'rejected')<span class="badge badge-danger">{{ __('messages.rejected') }}</span>
            @else<span class="badge badge-success">{{ __('messages.completed') }}</span>
            @endif
        </p>
        @if($travel->rejection_reason)<p><strong>{{ __('messages.rejection_reason') }}:</strong> {{ $travel->rejection_reason }}</p>@endif
        @if($travel->notes)<p><strong>{{ __('messages.notes') }}:</strong> {{ $travel->notes }}</p>@endif
    </div>
</div>
@endsection
