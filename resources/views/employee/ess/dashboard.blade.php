@extends('layouts.ess')

@section('title', 'Employee Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Info Boxes -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $todayAttendance ? 'Present' : 'Absent' }}</h3>
                <p>Today's Status</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="{{ route('ess.attendance') }}" class="small-box-footer">
                View Attendance <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pendingLeaves }}</h3>
                <p>Pending Leaves</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <a href="{{ route('ess.leaves') }}" class="small-box-footer">
                View Leaves <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $employee->department->name ?? 'N/A' }}</h3>
                <p>Department</p>
            </div>
            <div class="icon">
                <i class="fas fa-building"></i>
            </div>
            <a href="{{ route('ess.profile') }}" class="small-box-footer">
                View Profile <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $latestPayslip ? '₹' . number_format($latestPayslip->net_salary, 0) : 'N/A' }}</h3>
                <p>Latest Payslip</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="{{ route('ess.payslips') }}" class="small-box-footer">
                View Payslips <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Attendance -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Today's Attendance</h3>
            </div>
            <div class="card-body">
                @if($todayAttendance)
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Status:</th>
                            <td><span class="badge badge-success">Present</span></td>
                        </tr>
                        <tr>
                            <th>Check In:</th>
                            <td>{{ $todayAttendance->check_in_time ? \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('h:i A') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Check Out:</th>
                            <td>
                                @if($todayAttendance->check_out_time)
                                    {{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('h:i A') }}
                                @else
                                    <span class="text-warning">Not checked out</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">No attendance recorded for today.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Leaves -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Leaves</h3>
            </div>
            <div class="card-body p-0">
                @if($recentLeaves->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }}</td>
                                    <td>{{ $leave->total_days }}</td>
                                    <td>
                                        @if($leave->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($leave->status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted p-3">No leave requests found.</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('ess.leaves') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Attendance -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Attendance</h3>
            </div>
            <div class="card-body p-0">
                @if($recentAttendance->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendance as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                    <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') : 'N/A' }}</td>
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
                    <p class="text-muted p-3">No attendance records found.</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('ess.attendance') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>
    </div>
</div>
@endsection

