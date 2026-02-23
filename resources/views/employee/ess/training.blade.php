@extends('layouts.ess')
@section('title', __('messages.my_training'))
@section('page_title', __('messages.my_training'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.training') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.training_assignments') }}</h3>
        </div>
        <div class="card-body p-0">
            @if($assignments->isNotEmpty())
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.course') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th>{{ __('messages.duration') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.assigned') }}</th>
                            <th>{{ __('messages.completed') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $a)
                            <tr>
                                <td>{{ $a->course->name ?? '-' }}</td>
                                <td>{{ $a->course->type ?? '-' }}</td>
                                <td>{{ $a->course->duration_hours ?? 0 }} {{ __('messages.hrs_short') }}</td>
                                <td>{{ __('messages.' . $a->status) }}</td>
                                <td>{{ $a->assigned_at?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $a->completed_at?->format('d M Y') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-4 text-center text-muted">{{ __('messages.no_training_found') }}</div>
            @endif
        </div>
    </div>
@endsection