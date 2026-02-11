<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">{{ __('messages.leave_requests') }}</h3>
            @can('create leaves')
                <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> {{ __('messages.apply_leave') }}
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                        placeholder="{{ __('messages.search_employee') }}">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="status" class="form-control">
                        <option value="">{{ __('messages.all_status') }}</option>
                        <option value="pending">{{ __('messages.pending') }}</option>
                        <option value="hr_approved">{{ __('messages.hr_approved') }}</option>
                        <option value="approved">{{ __('messages.approved') }}</option>
                        <option value="rejected">{{ __('messages.rejected') }}</option>
                    </select>
                </div>
            </div>

            @error('approve')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror
            @error('reject')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.leave_type') }}</th>
                        <th>{{ __('messages.start_date') }}</th>
                        <th>{{ __('messages.end_date') }}</th>
                        <th>{{ __('messages.days') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>{{ $leave->employee->full_name ?? '-' }}</td>
                            <td>{{ $leave->leaveType->name ?? '-' }}</td>
                            <td>{{ $leave->start_date->format('d M Y') }}</td>
                            <td>{{ $leave->end_date->format('d M Y') }}</td>
                            <td>{{ $leave->total_days }}</td>
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
                            <td class="action-buttons">
                                <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-info"><i
                                        class="fas fa-eye"></i></a>
                                @can('directApprove', $leave)
                                    <button type="button" wire:click="directApprove({{ $leave->id }})"
                                        wire:loading.attr="disabled"
                                        class="btn btn-sm btn-success">{{ __('messages.approve') }}</button>
                                    <button type="button" wire:click="openRejectModal({{ $leave->id }})"
                                        class="btn btn-sm btn-danger">{{ __('messages.reject') }}</button>
                                @endcan
                                @can('hrApprove', $leave)
                                    <button type="button" wire:click="hrApprove({{ $leave->id }})" wire:loading.attr="disabled"
                                        class="btn btn-sm btn-success">{{ __('messages.hr_approve') }}</button>
                                    <button type="button" wire:click="openRejectModal({{ $leave->id }})"
                                        class="btn btn-sm btn-danger">{{ __('messages.reject') }}</button>
                                @endcan
                                @can('adminApprove', $leave)
                                    <button type="button" wire:click="adminApprove({{ $leave->id }})"
                                        wire:loading.attr="disabled"
                                        class="btn btn-sm btn-success">{{ __('messages.final_approve') }}</button>
                                    <button type="button" wire:click="openRejectModal({{ $leave->id }})"
                                        class="btn btn-sm btn-danger">{{ __('messages.reject') }}</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('messages.no_leave_requests') }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $leaves->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>

    @if($showRejectModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.reject_leave_request') }}</h5>
                        <button type="button" class="close" wire:click="closeRejectModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejectionReason">{{ __('messages.rejection_reason_required') }}</label>
                            <textarea wire:model.blur="rejectionReason" id="rejectionReason"
                                class="form-control @error('rejectionReason') is-invalid @enderror" rows="3"
                                placeholder="{{ __('messages.enter_reason') }}"></textarea>
                            @error('rejectionReason')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @error('reject')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeRejectModal">{{ __('messages.cancel') }}</button>
                        <button type="button" class="btn btn-danger" wire:click="submitReject"
                            wire:loading.attr="disabled">{{ __('messages.reject') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>