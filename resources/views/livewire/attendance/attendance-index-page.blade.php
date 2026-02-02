<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Attendance Records</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Employee</label>
                    <select wire:model.live="employeeId" class="form-control">
                        <option value="">All Employees</option>
                        @foreach($this->employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select wire:model.live="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date</label>
                    <input type="date" wire:model.live="filterDate" class="form-control">
                </div>
            </div>

            @if(auth()->user()->can('create attendance'))
                <div class="mb-3 p-2 bg-light rounded">
                    <strong>Quick Check-in</strong>
                    <div class="row align-items-end mt-1">
                        <div class="col-md-4">
                            <select wire:model="checkInEmployeeId" class="form-control">
                                <option value="">Select employee</option>
                                @foreach($this->employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" wire:click="checkIn" wire:loading.attr="disabled" class="btn btn-success">
                                <span wire:loading.remove>Check In</span>
                                <span wire:loading>...</span>
                            </button>
                        </div>
                        @error('checkIn')
                            <div class="col-12 text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                                <span class="badge badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td>
                                @if(!$attendance->check_out_time && auth()->user()->can('update attendance'))
                                    <button type="button" wire:click="checkOut({{ $attendance->id }})" wire:loading.attr="disabled" class="btn btn-sm btn-warning">
                                        Check Out
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No attendance records found.</td>
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
