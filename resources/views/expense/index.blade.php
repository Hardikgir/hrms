@extends('layouts.adminlte')

@section('title', __('messages.expenses'))
@section('page_title', __('messages.expenses'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.expenses') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.expenses') }}</h3>
            @if(auth()->user()->employee)
                <div class="card-tools">
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
                        {{ __('messages.submit_expense') }}</a>
                </div>
            @endif
        </div>
        <div class="card-body">
            @if($employees->isNotEmpty())
                <form method="GET" action="{{ route('expenses.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="employee_id" class="form-control">
                                <option value="">{{ __('messages.all_employees') }}</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    {{ __('messages.pending') }}</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    {{ __('messages.approved') }}</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    {{ __('messages.rejected') }}</option>
                                <option value="reimbursed" {{ request('status') == 'reimbursed' ? 'selected' : '' }}>
                                    {{ __('messages.reimbursed') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2"><button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                        </div>
                    </div>
                </form>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.amount') }}</th>
                        <th>{{ __('messages.category') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.submitted') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $e)
                        <tr>
                            <td>{{ $e->employee->full_name ?? '-' }}</td>
                            <td>{{ number_format($e->amount, 2) }}</td>
                            <td>{{ $e->category }}</td>
                            <td>
                                @if($e->status === 'pending')<span
                                    class="badge badge-warning">{{ __('messages.pending') }}</span>
                                @elseif($e->status === 'approved')<span
                                    class="badge badge-info">{{ __('messages.approved') }}</span>
                                @elseif($e->status === 'rejected')<span
                                    class="badge badge-danger">{{ __('messages.rejected') }}</span>
                                @else<span class="badge badge-success">{{ __('messages.reimbursed') }}</span>
                                @endif
                            </td>
                            <td>{{ $e->created_at->format('d M Y') }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('expenses.show', $e) }}" class="btn btn-sm btn-info"><i
                                        class="fas fa-eye"></i></a>
                                @can('approve', $e)
                                    @if($e->status === 'pending')
                                        <form action="{{ route('expenses.approve', $e) }}" method="POST" class="d-inline">@csrf<button
                                                type="submit" class="btn btn-sm btn-success">{{ __('messages.approve') }}</button>
                                        </form>
                                        <form action="{{ route('expenses.reject', $e) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('{{ __('messages.are_you_sure') }}');">@csrf<input type="text"
                                                name="reason" placeholder="{{ __('messages.reason') }}" required><button type="submit"
                                                class="btn btn-sm btn-danger">{{ __('messages.reject') }}</button></form>
                                    @endif
                                    @if($e->status === 'approved')
                                        <form action="{{ route('expenses.reimburse', $e) }}" method="POST" class="d-inline">@csrf<button
                                                type="submit"
                                                class="btn btn-sm btn-primary">{{ __('messages.mark_reimbursed') }}</button></form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_records_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $expenses->links() }}
        </div>
    </div>
@endsection