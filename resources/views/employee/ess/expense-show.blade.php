@extends('layouts.ess')

@section('title', __('messages.expense_details'))
@section('page_title', __('messages.expense_details'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.expenses') }}">{{ __('messages.expenses') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.view') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.expense_number', ['id' => $expense->id]) }}</h3>
            <div class="card-tools">
                <a href="{{ route('ess.expenses') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_expenses') }}</a>
            </div>
        </div>
        <div class="card-body">
            <p><strong>{{ __('messages.amount') }}:</strong> {{ number_format($expense->amount, 2) }}</p>
            <p><strong>{{ __('messages.category') }}:</strong> {{ $expense->category }}</p>
            <p><strong>{{ __('messages.description') }}:</strong> {{ $expense->description ?? '-' }}</p>
            <p><strong>{{ __('messages.status') }}:</strong>
                @if($expense->status === 'pending')<span class="badge badge-warning">{{ __('messages.pending') }}</span>
                @elseif($expense->status === 'approved')<span class="badge badge-info">{{ __('messages.approved') }}</span>
                @elseif($expense->status === 'rejected')<span
                    class="badge badge-danger">{{ __('messages.rejected') }}</span>
                @else<span class="badge badge-success">{{ __('messages.reimbursed') }}</span>
                @endif
            </p>
            @if($expense->rejection_reason)
            <p><strong>{{ __('messages.rejection_reason') }}:</strong> {{ $expense->rejection_reason }}</p>@endif
            @if($expense->receipt_path)
                <p><strong>{{ __('messages.receipt') }}:</strong>
                    <a href="{{ route('expenses.receipt', $expense) }}" class="btn btn-sm btn-outline-primary" target="_blank"
                        rel="noopener">
                        <i class="fas fa-download"></i> {{ __('messages.download_receipt') }}
                    </a>
                </p>
            @endif
        </div>
    </div>
@endsection