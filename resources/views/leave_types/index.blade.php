@extends('layouts.adminlte')

@section('title', __('messages.leave_types'))
@section('page_title', __('messages.leave_types'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">{{ __('messages.leave_types') }}</h3>
                <a href="{{ route('leave-types.create') }}" class="btn btn-primary btn-sm ml-auto mr-auto">
                    <i class="fas fa-plus"></i> {{ __('messages.create_leave_type') }}
                </a>
            </div>
            
            <div class="card-body p-0 table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.code') }}</th>
                            <th>{{ __('messages.max_days') }}</th>
                            <th>{{ __('messages.is_paid') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th class="text-right" style="width: 150px;">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveTypes as $leaveType)
                            <tr>
                                <td>{{ $leaveType->name }}</td>
                                <td><span class="badge badge-info">{{ $leaveType->code }}</span></td>
                                <td>{{ $leaveType->max_days_per_year ?? 'Unlimited' }}</td>
                                <td>
                                    @if($leaveType->is_paid)
                                        <span class="badge badge-success">{{ __('messages.yes') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('messages.no') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leaveType->is_active)
                                        <span class="badge badge-success">{{ __('messages.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('messages.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('leave-types.edit', $leaveType) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('leave-types.destroy', $leaveType) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                    {{ __('messages.no_leave_types_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($leaveTypes->hasPages())
                <div class="card-footer">
                    {{ $leaveTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
