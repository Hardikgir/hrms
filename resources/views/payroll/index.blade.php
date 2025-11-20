@extends('layouts.adminlte')

@section('title', 'Payroll')
@section('page_title', 'Payroll Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Payroll</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payroll Records</h3>
        <div class="card-tools">
            @can('run payroll')
            <a href="{{ route('payroll.run') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-calculator"></i> Run Payroll
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('payroll.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <input type="number" name="year" class="form-control" placeholder="Year" value="{{ request('year', date('Y')) }}" min="2020" max="2099">
                </div>
                <div class="col-md-2">
                    <select name="month" class="form-control">
                        <option value="">All Months</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Period</th>
                    <th>Gross Salary</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->employee->full_name ?? '-' }}</td>
                    <td>{{ $payroll->month && $payroll->year ? date('F Y', mktime(0, 0, 0, $payroll->month, 1)) . ' ' . $payroll->year : '-' }}</td>
                    <td>₹{{ $payroll->gross_salary ? number_format($payroll->gross_salary, 2) : '0.00' }}</td>
                    <td>₹{{ $payroll->total_deductions ? number_format($payroll->total_deductions, 2) : '0.00' }}</td>
                    <td>₹{{ $payroll->net_salary ? number_format($payroll->net_salary, 2) : '0.00' }}</td>
                    <td>
                        <span class="badge badge-{{ $payroll->status == 'paid' ? 'success' : ($payroll->status == 'approved' ? 'info' : 'warning') }}">
                            {{ ucfirst($payroll->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No payroll records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $payrolls->links() }}
        </div>
    </div>
</div>
@endsection

