@extends('layouts.ess')

@section('title', __('messages.employee_dashboard'))
@section('page_title', __('messages.dashboard'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('messages.dashboard') }}</li>
@endsection

@section('content')
    <div class="row">
        <!-- Info Boxes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $todayAttendance ? __('messages.present') : __('messages.absent') }}</h3>
                    <p>{{ __('messages.todays_status') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <a href="{{ route('ess.attendance') }}" class="small-box-footer">
                    {{ __('messages.view_attendance') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingLeaves }}</h3>
                    <p>{{ __('messages.pending_leaves') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <a href="{{ route('ess.leaves') }}" class="small-box-footer">
                    {{ __('messages.view_leaves') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $employee->department->name ?? __('messages.n_a') }}</h3>
                    <p>{{ __('messages.department') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('ess.profile') }}" class="small-box-footer">
                    {{ __('messages.view_profile') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $latestPayslip ? __('messages.currency_symbol') . number_format($latestPayslip->net_salary, 0) : __('messages.n_a') }}
                    </h3>
                    <p>{{ __('messages.latest_payslip') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('ess.payslips') }}" class="small-box-footer">
                    {{ __('messages.view_payslips') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Attendance -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.todays_attendance') }}</h3>
                </div>
                <div class="card-body">
                    @if($todayAttendance)
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">{{ __('messages.status') }}:</th>
                                <td><span class="badge badge-success">{{ __('messages.present') }}</span></td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.check_in') }}:</th>
                                <td>{{ $todayAttendance->check_in_time ? \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('h:i A') : __('messages.n_a') }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.check_out') }}:</th>
                                <td>
                                    @if($todayAttendance->check_out_time)
                                        {{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('h:i A') }}
                                    @else
                                        <span class="text-warning">{{ __('messages.not_checked_out') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted">{{ __('messages.no_attendance_today') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Leaves -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.recent_leaves') }}</h3>
                </div>
                <div class="card-body p-0">
                    @if($recentLeaves->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.type') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.days') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLeaves as $leave)
                                    <tr>
                                        <td>{{ $leave->leaveType->name ?? __('messages.n_a') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }}</td>
                                        <td>{{ $leave->total_days }}</td>
                                        <td>
                                            @if($leave->status === 'approved')
                                                <span class="badge badge-success">{{ __('messages.approved') }}</span>
                                            @elseif($leave->status === 'rejected')
                                                <span class="badge badge-danger">{{ __('messages.rejected') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ __('messages.pending') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted p-3">{{ __('messages.no_requests_found') }}</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('ess.leaves') }}" class="btn btn-sm btn-primary">{{ __('messages.view_all') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Attendance -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.recent_attendance') }}</h3>
                </div>
                <div class="card-body p-0">
                    @if($recentAttendance->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.check_in') }}</th>
                                    <th>{{ __('messages.check_out') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendance as $attendance)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                        <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') : __('messages.n_a') }}
                                        </td>
                                        <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') : __('messages.n_a') }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'late' ? 'warning' : 'danger') }}">
                                                @if($attendance->status === 'present') {{ __('messages.present') }}
                                                @elseif($attendance->status === 'late') {{ __('messages.late') }}
                                                @elseif($attendance->status === 'absent') {{ __('messages.absent') }}
                                                @else {{ ucfirst($attendance->status) }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted p-3">{{ __('messages.no_records_found') }}</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('ess.attendance') }}" class="btn btn-sm btn-primary">{{ __('messages.view_all') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection