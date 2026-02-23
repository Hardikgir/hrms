@extends('layouts.ess')

@section('title', __('messages.my_attendance'))
@section('page_title', __('messages.my_attendance'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.attendance') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        @livewire('attendance.check-in-out-widget')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('messages.attendance_records') }}</h3>
                <div class="card-tools">
                    <form method="GET" action="{{ route('ess.attendance') }}" class="form-inline">
                        <select name="month" class="form-control form-control-sm mr-2">
                            <option value="">{{ __('messages.all_months') }}</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    {{ __('messages.' . strtolower(date('F', mktime(0, 0, 0, $i, 1)))) }}
                                </option>
                            @endfor
                        </select>
                        <input type="number" name="year" class="form-control form-control-sm mr-2" 
                               placeholder="{{ __('messages.year') }}" value="{{ request('year', date('Y')) }}" min="2020" max="2099">
                        <button type="submit" class="btn btn-sm btn-primary">{{ __('messages.filter') }}</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @if($attendances->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.check_in') }}</th>
                                <th>{{ __('messages.check_out') }}</th>
                                <th>{{ __('messages.working_hours') }}</th>
                                <th>{{ __('messages.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                    <td>
                                        @if($attendance->check_in_time)
                                            {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}
                                        @else
                                            <span class="text-muted">{{ __('messages.n_a') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_out_time)
                                            {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}
                                        @else
                                            <span class="text-warning">{{ __('messages.not_checked_out') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_in_time && $attendance->check_out_time)
                                            @php
                                                $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                                $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
                                                $totalHours = $checkIn->diffInMinutes($checkOut) / 60;
                                            @endphp
                                            {{ number_format($totalHours, 1) }} {{ __('messages.hrs_short') }}
                                        @else
                                            <span class="text-muted">{{ __('messages.n_a') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->status === 'present' ? 'success' : 'danger' }}">
                                            {{ __('messages.' . $attendance->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3">
                        <p class="text-muted">{{ __('messages.no_records_found') }}</p>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

