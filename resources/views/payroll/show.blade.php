@extends('layouts.adminlte')

@section('title', 'View Payslip')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Payslip Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li>
                    <li class="breadcrumb-item active">View Payslip</li>
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
                            Payslip - {{ $payroll->employee->full_name ?? 'N/A' }} 
                            ({{ \Carbon\Carbon::create($payroll->year, $payroll->month, 1)->format('F Y') }})
                        </h3>
                        <div class="card-tools">
                            @if($payroll->status === 'draft' && auth()->user()->can('update payroll'))
                                <form action="{{ route('payroll.lock', $payroll) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning mr-1"><i class="fas fa-lock"></i> Lock</button>
                                </form>
                            @endif
                            @if(in_array($payroll->status, ['draft', 'processed']) && auth()->user()->can('update payroll'))
                                <form action="{{ route('payroll.approve', $payroll) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info mr-1"><i class="fas fa-check"></i> Approve</button>
                                </form>
                            @endif
                            <a href="{{ route('payroll.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Employee Information</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Employee ID:</th>
                                        <td>{{ $payroll->employee->employee_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $payroll->employee->full_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Department:</th>
                                        <td>{{ $payroll->employee->department->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Designation:</th>
                                        <td>{{ $payroll->employee->designation->name ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Pay Period</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Pay Period:</th>
                                        <td>{{ \Carbon\Carbon::parse($payroll->pay_period_start)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($payroll->pay_period_end)->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Working Days:</th>
                                        <td>{{ $payroll->working_days }}</td>
                                    </tr>
                                    <tr>
                                        <th>Present Days:</th>
                                        <td>{{ $payroll->present_days }}</td>
                                    </tr>
                                    <tr>
                                        <th>Leave Days:</th>
                                        <td>{{ $payroll->leave_days }}</td>
                                    </tr>
                                    <tr>
                                        <th>Absent Days:</th>
                                        <td>{{ $payroll->absent_days }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($payroll->status === 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($payroll->status === 'approved')
                                                <span class="badge badge-info">Approved</span>
                                            @elseif($payroll->status === 'processed')
                                                <span class="badge badge-warning">Processed</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($payroll->status) }}</span>
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
                                        <h3 class="card-title">Earnings</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Component</th>
                                                    <th class="text-right">Amount (₹)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($payroll->earnings)
                                                    @foreach($payroll->earnings as $key => $value)
                                                        <tr>
                                                            <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                            <td class="text-right">{{ number_format($value, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>Basic Salary</td>
                                                        <td class="text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
                                                    </tr>
                                                @endif
                                                <tr class="table-success">
                                                    <th>Gross Salary</th>
                                                    <th class="text-right">₹{{ number_format($payroll->gross_salary, 2) }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title">Deductions</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Component</th>
                                                    <th class="text-right">Amount (₹)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($payroll->deductions)
                                                    @foreach($payroll->deductions as $key => $value)
                                                        <tr>
                                                            <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                            <td class="text-right">{{ number_format($value, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <tr class="table-danger">
                                                    <th>Total Deductions</th>
                                                    <th class="text-right">₹{{ number_format($payroll->total_deductions, 2) }}</th>
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
                                        <h3 class="card-title">Net Salary</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>Gross Salary: ₹{{ number_format($payroll->gross_salary, 2) }}</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <h4>Total Deductions: ₹{{ number_format($payroll->total_deductions, 2) }}</h4>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2 class="text-center">
                                                    <strong>Net Salary: ₹{{ number_format($payroll->net_salary, 2) }}</strong>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($payroll->statutory)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Statutory Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($payroll->statutory as $key => $value)
                                                <div class="col-md-3">
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                    ₹{{ number_format($value, 2) }}
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
                                        <h3 class="card-title">Notes</h3>
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
                                        <h3 class="card-title">Audit Log</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                    <th>User</th>
                                                    <th>Old Status</th>
                                                    <th>New Status</th>
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

