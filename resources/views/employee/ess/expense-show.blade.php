@extends('layouts.ess')

@section('title', 'Expense Details')
@section('page_title', 'Expense Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.expenses') }}">Expenses</a></li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Expense #{{ $expense->id }}</h3>
        <div class="card-tools">
            <a href="{{ route('ess.expenses') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Back to Expenses</a>
        </div>
    </div>
    <div class="card-body">
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
        @if($expense->receipt_path)
        <p><strong>Receipt:</strong>
            <a href="{{ route('expenses.receipt', $expense) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">
                <i class="fas fa-download"></i> View / Download receipt
            </a>
        </p>
        @endif
    </div>
</div>
@endsection
