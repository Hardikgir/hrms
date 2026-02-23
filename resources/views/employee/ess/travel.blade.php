@extends('layouts.ess')
@section('title', __('messages.my_travel'))
@section('page_title', __('messages.my_travel_requests'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.travel') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.my_travel_requests') }}</h3>
            <div class="card-tools">
                <a href="{{ route('ess.travel.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                    {{ __('messages.new_request') }}</a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($requests->total() > 0)
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.purpose') }}</th>
                            <th>{{ __('messages.dates') }}</th>
                            <th>{{ __('messages.estimated') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $r)
                            <tr>
                                <td>{{ Str::limit($r->purpose, 35) }}</td>
                                <td>{{ $r->start_date->format('d M') }} – {{ $r->end_date->format('d M Y') }}</td>
                                <td>{{ $r->estimated_amount ? number_format($r->estimated_amount, 2) : '-' }}</td>
                                <td>@if($r->status === 'pending')<span
                                class="badge badge-warning">{{ __('messages.pending') }}</span>@elseif($r->status === 'approved')<span
                                        class="badge badge-info">{{ __('messages.approved') }}</span>@elseif($r->status === 'rejected')<span
                                        class="badge badge-danger">{{ __('messages.rejected') }}</span>@else<span
                                        class="badge badge-success">{{ __('messages.completed') }}</span>@endif</td>
                                <td class="action-buttons"><a href="{{ route('ess.travel.show', $r) }}"
                                        class="btn btn-sm btn-info">{{ __('messages.view') }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3">{{ $requests->links() }}</div>
            @else
                <div class="p-4 text-center text-muted">{{ __('messages.no_record') }}. <a
                        href="{{ route('ess.travel.create') }}">{{ __('messages.submit_a_request') }}</a>.</div>
            @endif
        </div>
    </div>
@endsection