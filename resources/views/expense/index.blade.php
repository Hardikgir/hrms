@extends('layouts.adminlte')

@section('title', 'Expenses')
@section('page_title', 'Expenses')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Expenses</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Expenses</h3>
        @if(auth()->user()->employee)
        <div class="card-tools">
            <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Submit Expense</a>
        </div>
        @endif
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('error') }}</div>@endif
        @if($employees->isNotEmpty())
        <form method="GET" action="{{ route('expenses.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="employee_id" class="form-control">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="reimbursed" {{ request('status') == 'reimbursed' ? 'selected' : '' }}>Reimbursed</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">Filter</button></div>
            </div>
        </form>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $e)
                <tr>
                    <td>{{ $e->employee->full_name ?? '-' }}</td>
                    <td>{{ number_format($e->amount, 2) }}</td>
                    <td>{{ $e->category }}</td>
                    <td>
                        @if($e->status === 'pending')<span class="badge badge-warning">Pending</span>
                        @elseif($e->status === 'approved')<span class="badge badge-info">Approved</span>
                        @elseif($e->status === 'rejected')<span class="badge badge-danger">Rejected</span>
                        @else<span class="badge badge-success">Reimbursed</span>
                        @endif
                    </td>
                    <td>{{ $e->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('expenses.show', $e) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        @can('approve', $e)
                        @if($e->status === 'pending')
                        <form action="{{ route('expenses.approve', $e) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-success">Approve</button></form>
                        <form action="{{ route('expenses.reject', $e) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject?');">@csrf<input type="text" name="reason" placeholder="Reason" required><button type="submit" class="btn btn-sm btn-danger">Reject</button></form>
                        @endif
                        @if($e->status === 'approved')
                        <form action="{{ route('expenses.reimburse', $e) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-primary">Mark Reimbursed</button></form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No expenses.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $expenses->links() }}
    </div>
</div>
@endsection
