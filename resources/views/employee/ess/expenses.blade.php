@extends('layouts.ess')
@section('title', __('messages.my_expenses'))
@section('page_title', __('messages.my_expenses'))
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.expenses') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.my_expenses') }}</h3>
            <div class="card-tools">
                <a href="{{ route('ess.expenses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                    {{ __('messages.submit_expense') }}</a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($expenses->total() > 0)
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.amount') }}</th>
                            <th>{{ __('messages.category') }}</th>
                            <th>{{ __('messages.description') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $e)
                            <tr>
                                <td>{{ number_format($e->amount, 2) }}</td>
                                <td>{{ $e->category }}</td>
                                <td>{{ Str::limit($e->description, 40) ?? '-' }}</td>
                                <td>@if($e->status === 'pending')<span
                                class="badge badge-warning">{{ __('messages.pending') }}</span>@elseif($e->status === 'approved')<span
                                        class="badge badge-info">{{ __('messages.approved') }}</span>@elseif($e->status === 'rejected')<span
                                        class="badge badge-danger">{{ __('messages.rejected') }}</span>@else<span
                                        class="badge badge-success">{{ __('messages.reimbursed') }}</span>@endif</td>
                                <td>{{ $e->created_at->format('d M Y') }}</td>
                                <td class="action-buttons"><a href="{{ route('ess.expenses.show', $e) }}"
                                        class="btn btn-sm btn-info">{{ __('messages.view') }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-3">{{ $expenses->links() }}</div>
            @else
                <div class="p-4 text-center text-muted">{{ __('messages.no_expenses_yet') }} <a
                        href="{{ route('ess.expenses.create') }}">{{ __('messages.submit_an_expense') }}</a>.</div>
            @endif
        </div>
    </div>
@endsection