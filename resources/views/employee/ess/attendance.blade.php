@extends('layouts.ess')

@section('title', 'My Attendance')
@section('page_title', 'My Attendance')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Attendance</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        @livewire('attendance.check-in-out-widget')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Attendance Records</h3>
                <div class="card-tools">
                    <form method="GET" action="{{ route('ess.attendance') }}" class="form-inline">
                        <select name="month" class="form-control form-control-sm mr-2">
                            <option value="">All Months</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                        <input type="number" name="year" class="form-control form-control-sm mr-2" 
                               placeholder="Year" value="{{ request('year', date('Y')) }}" min="2020" max="2099">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @if($attendances->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Working Hours</th>
                                <th>Status</th>
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
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_out_time)
                                            {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}
                                        @else
                                            <span class="text-warning">Not checked out</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_in_time && $attendance->check_out_time)
                                            @php
                                                $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                                $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
                                                $hours = $checkIn->diffInHours($checkOut);
                                                $minutes = $checkIn->diffInMinutes($checkOut) % 60;
                                            @endphp
                                            {{ $hours }}h {{ $minutes }}m
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->status === 'present' ? 'success' : 'danger' }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3">
                        <p class="text-muted">No attendance records found.</p>
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

