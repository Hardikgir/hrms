@extends('layouts.ess')
@section('title', 'My Expenses')
@section('page_title', 'My Expenses')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Expenses</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Expenses</h3>
        <div class="card-tools">
            <a href="{{ route('ess.expenses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Submit Expense</a>
        </div>
    </div>
    <div class="card-body p-0">
        @if(session('success'))<div class="alert alert-success m-2">{{ session('success') }}</div>@endif
        @if($expenses->total() > 0)
        <table class="table table-striped mb-0">
            <thead><tr><th>Amount</th><th>Category</th><th>Description</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($expenses as $e)
                <tr>
                    <td>{{ number_format($e->amount, 2) }}</td>
                    <td>{{ $e->category }}</td>
                    <td>{{ Str::limit($e->description, 40) ?? '-' }}</td>
                    <td>@if($e->status === 'pending')<span class="badge badge-warning">Pending</span>@elseif($e->status === 'approved')<span class="badge badge-info">Approved</span>@elseif($e->status === 'rejected')<span class="badge badge-danger">Rejected</span>@else<span class="badge badge-success">Reimbursed</span>@endif</td>
                    <td>{{ $e->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('expenses.show', $e) }}" class="btn btn-sm btn-info">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $expenses->links() }}</div>
        @else
        <div class="p-4 text-center text-muted">No expenses yet. <a href="{{ route('ess.expenses.create') }}">Submit an expense</a>.</div>
        @endif
    </div>
</div>
@endsection
