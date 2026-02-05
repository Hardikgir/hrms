@extends('layouts.ess')

@section('title', 'My Payslips')
@section('page_title', 'My Payslips')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Payslips</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Payslips</h3>
                <div class="card-tools">
                    <form method="GET" action="{{ route('ess.payslips') }}" class="form-inline">
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
                @if($payrolls->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Gross Salary</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $payroll)
                                <tr>
                                    <td>{{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }}</td>
                                    <td>{{ $payroll->year }}</td>
                                    <td>₹{{ number_format($payroll->gross_salary, 2) }}</td>
                                    <td>₹{{ number_format($payroll->total_deductions, 2) }}</td>
                                    <td><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                                    <td>
                                        @if($payroll->status === 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($payroll->status === 'approved')
                                            <span class="badge badge-info">Approved</span>
                                        @else
                                            <span class="badge badge-warning">{{ ucfirst($payroll->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{{ route('ess.payslips.show', $payroll) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3">
                        <p class="text-muted">No payslips found.</p>
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

