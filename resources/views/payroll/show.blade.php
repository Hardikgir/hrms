@extends('layouts.adminlte')

@section('title', __('messages.view_payslip'))

@section('content')
    @php
        $earningsArray = is_array($payroll->earnings) ? $payroll->earnings : (is_string($payroll->earnings) ? json_decode($payroll->earnings, true) ?? [] : []);
        $deductionsArray = is_array($payroll->deductions) ? $payroll->deductions : (is_string($payroll->deductions) ? json_decode($payroll->deductions, true) ?? [] : []);
        $statutoryArray = is_array($payroll->statutory) ? $payroll->statutory : (is_string($payroll->statutory) ? json_decode($payroll->statutory, true) ?? [] : []);
    @endphp
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.payslip_details') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">{{ __('messages.payroll') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('messages.view_payslip') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ __('messages.payslip') }} - {{ $payroll->employee->full_name ?? 'N/A' }}
                                ({{ \Carbon\Carbon::create($payroll->year, $payroll->month, 1)->translatedFormat('F Y') }})
                            </h3>
                            <div class="card-tools">
                                @if($payroll->status === 'draft' && auth()->user()->can('update payroll'))
                                    <form action="{{ route('payroll.lock', $payroll) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning"><i class="fas fa-lock"></i>
                                            {{ __('messages.lock') }}</button>
                                    </form>
                                @endif
                                @if(in_array($payroll->status, ['draft', 'processed']) && auth()->user()->can('update payroll'))
                                    <form action="{{ route('payroll.approve', $payroll) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-check"></i>
                                            {{ __('messages.approve') }}</button>
                                    </form>
                                @endif
                                <a href="{{ route('payroll.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>{{ __('messages.employee_information') }}</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">{{ __('messages.employee_id') }}:</th>
                                            <td>{{ $payroll->employee->employee_id ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.name') }}:</th>
                                            <td>{{ $payroll->employee->full_name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.department') }}:</th>
                                            <td>{{ $payroll->employee->department->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.designation') }}:</th>
                                            <td>{{ $payroll->employee->designation->name ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>{{ __('messages.pay_period') }}</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">{{ __('messages.pay_period') }}:</th>
                                            <td>{{ \Carbon\Carbon::parse($payroll->pay_period_start)->format('d M Y') }} -
                                                {{ \Carbon\Carbon::parse($payroll->pay_period_end)->format('d M Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.working_days') }}:</th>
                                            <td>{{ $payroll->working_days }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.present_days') }}:</th>
                                            <td>{{ $payroll->present_days }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.leave_days') }}:</th>
                                            <td>{{ $payroll->leave_days }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.absent_days') }}:</th>
                                            <td>{{ $payroll->absent_days }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.status') }}:</th>
                                            <td>
                                                @if($payroll->status === 'paid')
                                                    <span class="badge badge-success">{{ __('messages.paid') }}</span>
                                                @elseif($payroll->status === 'approved')
                                                    <span class="badge badge-info">{{ __('messages.approved') }}</span>
                                                @elseif($payroll->status === 'processed')
                                                    <span class="badge badge-warning">{{ __('messages.processed') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-secondary">{{ __('messages.' . $payroll->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('messages.earnings') }}</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('messages.component') }}</th>
                                                        <th class="text-right">{{ __('messages.amount') }}
                                                            ({{ __('messages.currency_symbol') }})</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($earningsArray))
                                                        @foreach($earningsArray as $key => $value)
                                                            <tr>
                                                                <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                                <td class="text-right">{{ number_format((float) $value, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td>{{ __('messages.basic_salary') }}</td>
                                                            <td class="text-right">
                                                                {{ number_format($payroll->basic_salary, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr class="table-success">
                                                        <th>{{ __('messages.gross_salary') }}</th>
                                                        <th class="text-right">
                                                            {{ __('messages.currency_symbol') }}{{ number_format($payroll->gross_salary, 2) }}
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('messages.deductions') }}</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('messages.component') }}</th>
                                                        <th class="text-right">{{ __('messages.amount') }}
                                                            ({{ __('messages.currency_symbol') }})</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($deductionsArray))
                                                        @foreach($deductionsArray as $key => $value)
                                                            <tr>
                                                                <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                                <td class="text-right">{{ number_format((float) $value, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr class="table-danger">
                                                        <th>{{ __('messages.total_deductions') }}</th>
                                                        <th class="text-right">
                                                            {{ __('messages.currency_symbol') }}{{ number_format($payroll->total_deductions, 2) }}
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('messages.net_salary') }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h4>{{ __('messages.gross_salary') }}:
                                                        {{ __('messages.currency_symbol') }}{{ number_format($payroll->gross_salary, 2) }}
                                                    </h4>
                                                </div>
                                                <div class="col-md-6">
                                                    <h4>{{ __('messages.total_deductions') }}:
                                                        {{ __('messages.currency_symbol') }}{{ number_format($payroll->total_deductions, 2) }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h2 class="text-center">
                                                        <strong>{{ __('messages.net_salary') }}:
                                                            {{ __('messages.currency_symbol') }}{{ number_format($payroll->net_salary, 2) }}</strong>
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(!empty($statutoryArray))
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('messages.statutory_details') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach($statutoryArray as $key => $value)
                                                        <div class="col-md-3">
                                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            {{ __('messages.currency_symbol') }}{{ number_format((float) $value, 2) }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($payroll->notes)
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('messages.notes') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <p>{{ $payroll->notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(isset($auditLogs) && $auditLogs->count() > 0)
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('messages.audit_log') }}</h3>
                                            </div>
                                            <div class="card-body p-0">
                                                <table class="table table-sm table-striped mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('messages.date') }}</th>
                                                            <th>{{ __('messages.action') }}</th>
                                                            <th>{{ __('messages.user') }}</th>
                                                            <th>{{ __('messages.old_status') }}</th>
                                                            <th>{{ __('messages.new_status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($auditLogs as $log)
                                                            <tr>
                                                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                                                <td>{{ ucfirst($log->action) }}</td>
                                                                <td>{{ $log->user->name ?? '-' }}</td>
                                                                <td>{{ $log->old_status ?? '-' }}</td>
                                                                <td>{{ $log->new_status ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection