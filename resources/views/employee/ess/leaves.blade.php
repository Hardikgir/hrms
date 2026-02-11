@extends('layouts.ess')

@section('title', __('messages.my_leaves'))
@section('page_title', __('messages.my_leaves'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.leaves') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Leave balance --}}
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> {{ __('messages.leave_balance') }}</h3>
                </div>
                <div class="card-body">
                    @livewire('leave.leave-balance-dashboard')
                </div>
            </div>

            {{-- Quick apply + stats --}}
            <div class="row mb-3">
                <div class="col-md-8">
                    <a href="{{ route('ess.leaves.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.apply_for_leave') }}
                    </a>
                    <p class="text-muted small mt-2 mb-0">{{ __('messages.submit_leave_help') }}</p>
                </div>
                @if($leaves->total() > 0)
                    <div class="col-md-4 text-right">
                        <span class="badge badge-warning mr-1">{{ __('messages.pending') }}: {{ $pendingCount ?? 0 }}</span>
                        <span class="badge badge-success mr-1">{{ __('messages.approved') }}: {{ $approvedCount ?? 0 }}</span>
                        <span class="badge badge-danger">{{ __('messages.rejected') }}: {{ $rejectedCount ?? 0 }}</span>
                    </div>
                @endif
            </div>

            {{-- Leave requests --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.my_leave_requests') }}</h3>
                    <div class="card-tools">
                        <form method="GET" action="{{ route('ess.leaves') }}" class="form-inline">
                            <label class="mr-2 mb-0 small text-muted">{{ __('messages.status') }}</label>
                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">{{ __('messages.all') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    {{ __('messages.pending') }}</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    {{ __('messages.approved') }}</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    {{ __('messages.rejected') }}</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('messages.leave_type') }}</th>
                                        <th>{{ __('messages.period') }}</th>
                                        <th>{{ __('messages.days') }}</th>
                                        <th>{{ __('messages.reason') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th width="120">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $leave)
                                        <tr>
                                            <td><strong>{{ $leave->leaveType->name ?? __('messages.n_a') }}</strong></td>
                                            <td>
                                                {{ $leave->start_date->format('d M Y') }} – {{ $leave->end_date->format('d M Y') }}
                                            </td>
                                            <td>{{ $leave->total_days }} {{ $leave->total_days !== 1 ? __('messages.days_plural') : __('messages.day') }}</td>
                                            <td title="{{ $leave->reason }}">{{ str()->limit($leave->reason, 40) }}</td>
                                            <td>
                                                @if($leave->status === 'approved')
                                                    <span class="badge badge-success">{{ __('messages.approved') }}</span>
                                                @elseif($leave->status === 'hr_approved')
                                                    <span class="badge badge-info">{{ __('messages.hr_approved') }}</span>
                                                @elseif($leave->status === 'rejected')
                                                    <span class="badge badge-danger"
                                                        title="{{ $leave->rejection_reason ?? '' }}">{{ __('messages.rejected') }}</span>
                                                @elseif($leave->status === 'cancelled')
                                                    <span class="badge badge-secondary">{{ __('messages.cancelled') }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ __('messages.pending') }}</span>
                                                @endif
                                            </td>
                                            <td class="action-buttons">
                                                <a href="{{ route('ess.leaves.show', $leave) }}" class="btn btn-sm btn-outline-info"
                                                    title="View"><i class="fas fa-eye"></i></a>
                                                @if($leave->status === 'pending')
                                                    <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-outline-primary"
                                                        title="Edit"><i class="fas fa-edit"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('messages.no_leave_requests') }}</h5>
                            <p class="text-muted mb-3">{{ __('messages.havent_applied_leave') }}</p>
                            <a href="{{ route('ess.leaves.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                                {{ __('messages.apply_for_leave') }}</a>
                        </div>
                    @endif
                </div>
                @if($leaves->hasPages())
                    <div class="card-footer d-flex justify-content-center">
                        {{ $leaves->links('vendor.pagination.bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection