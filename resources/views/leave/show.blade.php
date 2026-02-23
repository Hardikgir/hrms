@extends('layouts.adminlte')

@section('title', __('messages.leave_details'))
@section('page_title', __('messages.leave_details'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">{{ __('messages.leaves') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.details') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.leave_request_details') }}</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>{{ __('messages.employee') }}</th>
                    <td>{{ $leave->employee->full_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.leave_type') }}</th>
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
                        <th>{{ __('messages.hr_approved_by') }}</th>
                        <td>{{ $leave->hrApprovedBy->name ?? '-' }} {{ __('messages.on') }} {{ $leave->hr_approved_at->format('d M Y H:i') }}</td>
                    </tr>
                @endif
                @if($leave->approved_at)
                    <tr>
                        <th>{{ __('messages.final_approved_by') }}</th>
                        <td>{{ $leave->approvedBy->name ?? '-' }} {{ __('messages.on') }} {{ $leave->approved_at->format('d M Y H:i') }}</td>
                    </tr>
                @endif
                @if($leave->rejected_at)
                    <tr>
                        <th>{{ __('messages.rejected_by') }}</th>
                        <td>{{ $leave->rejectedBy->name ?? '-' }} {{ __('messages.on') }} {{ $leave->rejected_at->format('d M Y H:i') }}</td>
                    </tr>
                @endif
            </table>

            <div class="mt-3">
                <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
        </div>
    </div>
@endsection