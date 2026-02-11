@extends('layouts.ess')
@section('title', __('messages.my_roster'))
@section('page_title', __('messages.shift_roster'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.roster') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.my_roster') }} – {{ __('messages.week_of') }}
                {{ $weekStart->format('d M Y') }}</h3>
            <div class="card-tools">
                <a href="{{ route('ess.roster', ['week' => $weekStart->copy()->subWeek()->toDateString()]) }}"
                    class="btn btn-sm btn-secondary">{{ __('messages.prev') }}</a>
                <a href="{{ route('ess.roster', ['week' => $weekStart->copy()->addWeek()->toDateString()]) }}"
                    class="btn btn-sm btn-secondary">{{ __('messages.next') }}</a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($roster->isNotEmpty())
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.shift') }}</th>
                            <th>{{ __('messages.time') }}</th>
                            <th>{{ __('messages.notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roster as $r)
                            <tr>
                                <td>{{ $r->assignment_date->format('D d M Y') }}</td>
                                <td>{{ $r->shift->name ?? '-' }}</td>
                                <td>{{ $r->shift ? \Carbon\Carbon::parse($r->shift->start_time)->format('H:i') . ' – ' . \Carbon\Carbon::parse($r->shift->end_time)->format('H:i') : '-' }}
                                </td>
                                <td>{{ $r->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-4 text-center text-muted">{{ __('messages.no_shift_assignments') }}</div>
            @endif
        </div>
    </div>
@endsection