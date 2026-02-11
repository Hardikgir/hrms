@extends('layouts.ess')

@section('title', __('messages.my_payslips'))
@section('page_title', __('messages.my_payslips'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.payslips') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.my_payslips') }}</h3>
                    <div class="card-tools">
                        <form method="GET" action="{{ route('ess.payslips') }}" class="form-inline">
                            <select name="month" class="form-control form-control-sm mr-2">
                                <option value="">{{ __('messages.all_months') }}</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                        {{ __('messages.' . strtolower(date('F', mktime(0, 0, 0, $i, 1)))) }}
                                    </option>
                                @endfor
                            </select>
                            <input type="number" name="year" class="form-control form-control-sm mr-2"
                                placeholder="{{ __('messages.year') }}" value="{{ request('year', date('Y')) }}" min="2020"
                                max="2099">
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('messages.filter') }}</button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($payrolls->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.month') }}</th>
                                    <th>{{ __('messages.year') }}</th>
                                    <th>{{ __('messages.gross_salary') }}</th>
                                    <th>{{ __('messages.deductions') }}</th>
                                    <th>{{ __('messages.net_salary') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                    <tr>
                                        <td>{{ __('messages.' . strtolower(date('F', mktime(0, 0, 0, $payroll->month, 1)))) }}</td>
                                        <td>{{ $payroll->year }}</td>
                                        <td>{{ __('messages.currency_symbol') }}{{ number_format($payroll->gross_salary, 2) }}</td>
                                        <td>{{ __('messages.currency_symbol') }}{{ number_format($payroll->total_deductions, 2) }}
                                        </td>
                                        <td><strong>{{ __('messages.currency_symbol') }}{{ number_format($payroll->net_salary, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($payroll->status === 'paid')
                                                <span class="badge badge-success">{{ __('messages.paid') }}</span>
                                            @elseif($payroll->status === 'approved')
                                                <span class="badge badge-info">{{ __('messages.approved') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ __('messages.' . $payroll->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            <a href="{{ route('ess.payslips.show', $payroll) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> {{ __('messages.view') }}
                                            </a>
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
                    {{ $payrolls->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection