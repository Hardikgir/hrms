@extends('layouts.adminlte')

@section('title', 'Expense Details')
@section('page_title', 'Expense Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('content')
@if(session('success'))<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('error') }}</div>@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Expense #{{ $expense->id }}</h3>
        <div class="card-tools">
            @can('approve', $expense)
            @if($expense->status === 'pending')
            <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-success">Approve</button></form>
            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal">Reject</button>
            @endif
            @if($expense->status === 'approved')
            <form action="{{ route('expenses.reimburse', $expense) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-primary">Mark Reimbursed</button></form>
            @endif
            @endcan
        </div>
    </div>
    <div class="card-body">
        <p><strong>Employee:</strong> {{ $expense->employee->full_name ?? '-' }}</p>
        <p><strong>Amount:</strong> {{ number_format($expense->amount, 2) }}</p>
        <p><strong>Category:</strong> {{ $expense->category }}</p>
        <p><strong>Description:</strong> {{ $expense->description ?? '-' }}</p>
        <p><strong>Status:</strong>
            @if($expense->status === 'pending')<span class="badge badge-warning">Pending</span>
            @elseif($expense->status === 'approved')<span class="badge badge-info">Approved</span>
            @elseif($expense->status === 'rejected')<span class="badge badge-danger">Rejected</span>
            @else<span class="badge badge-success">Reimbursed</span>
            @endif
        </p>
        @if($expense->rejection_reason)<p><strong>Rejection reason:</strong> {{ $expense->rejection_reason }}</p>@endif
        @if($expense->receipt_path)<p><strong>Receipt:</strong> Uploaded (stored)</p>@endif
    </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('expenses.reject', $expense) }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Reject Expense</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reason">Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Reject</button></div>
            </form>
        </div>
    </div>
</div>
<a href="{{ route('expenses.index') }}" class="btn btn-secondary mt-2">Back</a>
@endsection
