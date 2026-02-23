@extends('layouts.ess')

@section('title', __('messages.view_payslip'))
@section('page_title', __('messages.payslip'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.payslips') }}">{{ __('messages.payslips') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.view') }}</li>
@endsection

@section('content')
    @php
        $earningsArray = is_array($payroll->earnings) ? $payroll->earnings : (is_string($payroll->earnings) ? json_decode($payroll->earnings, true) ?? [] : []);
        $deductionsArray = is_array($payroll->deductions) ? $payroll->deductions : (is_string($payroll->deductions) ? json_decode($payroll->deductions, true) ?? [] : []);
        $statutoryArray = is_array($payroll->statutory) ? $payroll->statutory : (is_string($payroll->statutory) ? json_decode($payroll->statutory, true) ?? [] : []);
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ __('messages.payslip') }} - {{ $payroll->employee->full_name ?? __('messages.n_a') }}
                        ({{ \Carbon\Carbon::create($payroll->year, $payroll->month, 1)->translatedFormat('F Y') }})
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('ess.payslips') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_payslips') }}
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
                                        @if($payroll->status === 'paid')<span
                                            class="badge badge-success">{{ __('messages.paid') }}</span>
                                        @elseif($payroll->status === 'approved')<span
                                            class="badge badge-info">{{ __('messages.approved') }}</span>
                                        @elseif($payroll->status === 'processed')<span
                                            class="badge badge-warning">{{ __('messages.processed') }}</span>
                                        @else<span
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
                                                    <td class="text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
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
                                            <h2 class="text-center"><strong>{{ __('messages.net_salary') }}:
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
                                                <div class="col-md-3"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                    {{ __('messages.currency_symbol') }}{{ number_format((float) $value, 2) }}</div>
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
                </div>
            </div>
        </div>
    </div>
@endsection