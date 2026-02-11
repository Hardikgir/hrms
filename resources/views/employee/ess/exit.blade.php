@extends('layouts.ess')
@section('title', __('messages.my_exit'))
@section('page_title', __('messages.my_exit_resignation'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.exit') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.my_exit_requests') }}</h3>
            <div class="card-tools">
                <a href="{{ route('ess.exit.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                    {{ __('messages.submit_resignation') }}</a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($exits->total() > 0)
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.resignation_date_table') }}</th>
                            <th>{{ __('messages.last_working_day') }}</th>
                            <th>{{ __('messages.reason') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exits as $e)
                            <tr>
                                <td>{{ $e->resignation_date->format('d M Y') }}</td>
                                <td>{{ $e->last_working_date->format('d M Y') }}</td>
                                <td>{{ $e->reason ?? '-' }}</td>
                                <td>{{ __('messages.' . $e->status) }}</td>
                                <td class="action-buttons"><a href="{{ route('exit.show', $e) }}"
                                        class="btn btn-sm btn-info">{{ __('messages.view') }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3">{{ $exits->links() }}</div>
            @else
                <div class="p-4 text-center text-muted">{{ __('messages.no_exit_requests_found') }} <a
                        href="{{ route('ess.exit.create') }}">{{ __('messages.submit_resignation') }}</a>.</div>
            @endif
        </div>
    </div>
@endsection