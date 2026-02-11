@extends('layouts.ess')

@section('title', __('messages.leave_details'))
@section('page_title', __('messages.leave_details'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('ess.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ess.leaves') }}">{{ __('messages.leaves') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.details') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.leave_request_details') }}</h3>
            <div class="card-tools">
                <a href="{{ route('ess.leaves') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_leaves') }}</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="25%">{{ __('messages.leave_type') }}</th>
                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.start_date') }}</th>
                    <td>{{ $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.end_date') }}</th>
                    <td>{{ $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.total_days') }}</th>
                    <td>{{ $leave->total_days }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.reason') }}</th>
                    <td>{{ $leave->reason }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.status') }}</th>
                    <td>
                        @if($leave->status === 'approved')
                            <span class="badge badge-success">{{ __('messages.approved') }}</span>
                        @elseif($leave->status === 'hr_approved')
                            <span class="badge badge-info">{{ __('messages.hr_approved') }}</span>
                        @elseif($leave->status === 'rejected')
                            <span class="badge badge-danger">{{ __('messages.rejected') }}</span>
                        @elseif($leave->status === 'cancelled')
                            <span class="badge badge-secondary">{{ __('messages.cancelled') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('messages.pending') }}</span>
                        @endif
                    </td>
                </tr>
                @if($leave->hr_approved_at)
                    <tr>
                        <th>{{ __('messages.hr_approve') }}</th>
                        <td>{{ $leave->hrApprovedBy->name ?? '-' }} {{ __('messages.on') }} {{ $leave->hr_approved_at->format('d M Y H:i') }}</td>
                    </tr>
                @endif
                @if($leave->approved_at)
                    <tr>
                        <th>{{ __('messages.final_approve') }}</th>
                        <td>{{ $leave->approvedBy->name ?? '-' }} {{ __('messages.on') }} {{ $leave->approved_at->format('d M Y H:i') }}</td>
                    </tr>
                @endif
                @if($leave->status === 'rejected' && ($leave->rejection_reason || $leave->rejected_at))
                    <tr>
                        <th>{{ __('messages.reject_leave_request') }}</th>
                        <td>
                            {{ $leave->rejection_reason ?? __('messages.no_reason_provided') }}
                            @if($leave->rejected_at)
                                <div class="text-muted small mt-1">{{ __('messages.rejected_on') }} {{ $leave->rejected_at->format('d M Y H:i') }}</div>
                            @endif
                        </td>
                    </tr>
                @endif
            </table>

            @if($leave->status === 'pending')
                <div class="mt-3">
                    <a href="{{ route('ess.leaves.edit', $leave) }}" class="btn btn-primary">{{ __('messages.edit_leave') }}</a>
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('ess.leaves') }}" class="btn btn-secondary">{{ __('messages.back_to_my_leaves') }}</a>
            </div>
        </div>
    </div>
@endsection