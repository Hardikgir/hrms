<div>
    @if(session('settings_saved'))
        <div class="alert alert-success alert-dismissible small">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('settings_saved') }}
        </div>
    @endif

    @if(auth()->user()->hasRole('Super Admin'))
        <div class="card card-outline card-info mb-4">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cog mr-1"></i> {{ __('messages.attendance') }}
                    {{ __('messages.settings') }}
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">{{ __('messages.attendance_settings_help', ['default' => 'Set the maximum check-in time. Check-ins after this time will be marked as Late.']) }}</p>
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label mb-0">{{ __('messages.max_check_in_time') }}</label>
                        <input type="time" wire:model="maxCheckInTime" class="form-control" step="300">
                        @error('maxCheckInTime')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="button" wire:click="saveMaxCheckInTime" wire:loading.attr="disabled"
                            class="btn btn-primary">
                            <span wire:loading.remove>{{ __('messages.save') }}</span>
                            <span wire:loading>...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.attendance_records') }}</h3>
            <div class="card-tools">
                <button type="button" wire:click="exportCsv" class="btn btn-success btn-sm">
                    <i class="fas fa-file-csv"></i> {{ __('messages.export_csv') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="small">{{ __('messages.employee') }}</label>
                    <select wire:model.live="employeeId" class="form-control form-control-sm">
                        <option value="">{{ __('messages.all_employees') }}</option>
                        @foreach($this->employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small">{{ __('messages.department') }}</label>
                    <select wire:model.live="departmentId" class="form-control form-control-sm">
                        <option value="">{{ __('messages.all_departments') }}</option>
                        @foreach($this->departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small">{{ __('messages.status') }}</label>
                    <select wire:model.live="status" class="form-control form-control-sm">
                        <option value="">{{ __('messages.all_status') }}</option>
                        <option value="present">{{ __('messages.present') }}</option>
                        <option value="absent">{{ __('messages.absent') }}</option>
                        <option value="late">{{ __('messages.late') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small">{{ __('messages.date') }}</label>
                    <input type="date" wire:model.live="filterDate" class="form-control form-control-sm">
                </div>
            </div>

            @if(auth()->user()->can('create attendance'))
                <div class="mb-3 p-2 bg-light rounded">
                    <strong>{{ __('messages.quick_check_in') }}</strong>
                    <div class="row align-items-end mt-1">
                        <div class="col-md-4">
                            <select wire:model="checkInEmployeeId" class="form-control">
                                <option value="">{{ __('messages.select_employee') }}</option>
                                @foreach($this->employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" wire:click="checkIn" wire:loading.attr="disabled" class="btn btn-success">
                                <span wire:loading.remove>{{ __('messages.check_in') }}</span>
                                <span wire:loading>...</span>
                            </button>
                        </div>
                        @error('checkInEmployeeId')
                            <div class="col-12 text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endif

            <table class="table table-sm table-bordered table-striped mt-2">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.check_in') }}</th>
                        <th>{{ __('messages.check_out') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->employee->full_name ?? '-' }}</td>
                            <td>{{ $attendance->date->format('d M Y') }}</td>
                            <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}</td>
                            <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}</td>
                            <td>
                                @if ($attendance->status === 'present')
                                    <span class="badge badge-success">{{ __('messages.present') }}</span>
                                @elseif($attendance->status === 'late')
                                    <span class="badge badge-warning">{{ __('messages.late') }}</span>
                                @elseif($attendance->status === 'absent')
                                    <span class="badge badge-danger">{{ __('messages.absent') }}</span>
                                @endif
                            </td>
                            <td class="action-buttons">
                                @if(!$attendance->check_out_time && auth()->user()->can('update attendance'))
                                    <button type="button" wire:click="checkOut({{ $attendance->id }})"
                                        wire:loading.attr="disabled" class="btn btn-sm btn-warning">
                                        {{ __('messages.check_out') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('messages.no_records_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $attendances->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>