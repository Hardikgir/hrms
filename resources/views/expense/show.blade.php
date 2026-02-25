@extends('layouts.adminlte')

@section('title', __('messages.expense_details'))
@section('page_title', __('messages.expense_details'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">{{ __('messages.expenses') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.view') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.expense_number', ['id' => $expense->id]) }}</h3>
            <div class="card-tools">
                @can('approve', $expense)
                    @if($expense->status === 'pending')
                        <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="d-inline">@csrf<button
                                type="submit" class="btn btn-sm btn-success">{{ __('messages.approve') }}</button></form>
                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                            data-target="#rejectModal">{{ __('messages.reject') }}</button>
                    @endif
                    @if($expense->status === 'approved')
                        <form action="{{ route('expenses.reimburse', $expense) }}" method="POST" class="d-inline">@csrf<button
                                type="submit" class="btn btn-sm btn-primary">{{ __('messages.mark_as_reimbursed') }}</button></form>
                    @endif
                @endcan
            </div>
        </div>
        <div class="card-body">
            <p><strong>{{ __('messages.employee') }}:</strong> {{ $expense->employee->full_name ?? '-' }}</p>
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
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('expenses.reject', $expense) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.reject_expense') }}</h5><button type="button" class="close"
                            data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason">{{ __('messages.reason') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('messages.cancel') }}</button><button type="submit"
                            class="btn btn-danger">{{ __('messages.reject') }}</button></div>
                </form>
            </div>
        </div>
    </div>
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary mt-2">{{ __('messages.back') }}</a>
@endsection