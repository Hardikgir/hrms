@extends('layouts.adminlte')

@section('title', __('messages.dashboard'))
@section('page_title', __('messages.dashboard'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('messages.dashboard') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ \App\Modules\Employee\Models\Employee::where('is_active', true)->count() }}</h3>
                    <p>{{ __('messages.active_employees') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('employees.index') }}" class="small-box-footer">
                    {{ __('messages.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Modules\Attendance\Models\Attendance::whereDate('date', today())->where('status', 'present')->count() }}
                    </h3>
                    <p>{{ __('messages.present_today') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <a href="{{ route('attendance.index') }}" class="small-box-footer">
                    {{ __('messages.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ \App\Modules\Leave\Models\Leave::where('status', 'pending')->count() }}</h3>
                    <p>{{ __('messages.pending_leaves') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <a href="{{ route('leaves.index') }}" class="small-box-footer">
                    {{ __('messages.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        @can('view payroll')
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ \App\Modules\Payroll\Models\Payroll::where('status', 'draft')->count() }}</h3>
                        <p>{{ __('messages.pending_payroll') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <a href="{{ route('payroll.index') }}" class="small-box-footer">
                            {{ __('messages.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endcan
    </div>
@endsection