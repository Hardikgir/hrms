@extends('layouts.adminlte')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Modules\Employee\Models\Employee::where('is_active', true)->count() }}</h3>
                <p>Active Employees</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('employees.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ \App\Modules\Attendance\Models\Attendance::whereDate('date', today())->where('status', 'present')->count() }}</h3>
                <p>Present Today</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="{{ route('attendance.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ \App\Modules\Leave\Models\Leave::where('status', 'pending')->count() }}</h3>
                <p>Pending Leaves</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <a href="{{ route('leaves.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    @can('view payroll')
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ \App\Modules\Payroll\Models\Payroll::where('status', 'draft')->count() }}</h3>
                <p>Pending Payroll</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="{{ route('payroll.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    @endcan
</div>
@endsection

