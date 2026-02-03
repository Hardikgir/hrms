<?php

namespace App\Livewire\Attendance;

use App\Modules\Attendance\Models\AttendanceSetting;
use App\Modules\Attendance\Services\AttendanceService;
use App\Modules\Employee\Models\Employee;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceIndexPage extends Component
{
    use WithPagination;

    public ?string $employeeId = null;
    public ?string $status = null;
    public ?string $filterDate = null;

    /** For admin quick check-in: selected employee to check in */
    public ?string $checkInEmployeeId = null;

    /** Max check-in time (H:i) – after this time check-in counts as late */
    public string $maxCheckInTime = '';

    protected $queryString = [
        'employeeId' => ['except' => ''],
        'status' => ['except' => ''],
        'filterDate' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->maxCheckInTime = AttendanceSetting::getMaxCheckInTime();
    }

    public function saveMaxCheckInTime(): void
    {
        $this->authorize('view attendance');
        $this->validate([
            'maxCheckInTime' => ['required', 'date_format:H:i'],
        ], [
            'maxCheckInTime.required' => 'Please set a time.',
            'maxCheckInTime.date_format' => 'Time must be in HH:MM format (e.g. 09:30).',
        ]);
        AttendanceSetting::setMaxCheckInTime($this->maxCheckInTime);
        $this->dispatch('attendance-updated');
        session()->flash('settings_saved', 'Max check-in time updated. Check-ins after this time will be marked as late.');
    }

    public function getEmployeesProperty()
    {
        return Employee::orderBy('first_name')->get();
    }

    public function checkIn(): void
    {
        $user = auth()->user();
        $employee = $user->employee;
        if (!$employee) {
            $this->authorize('create attendance');
            if (!$this->checkInEmployeeId) {
                $this->addError('checkInEmployeeId', 'Select an employee to check in.');
                return;
            }
            $employeeId = (int) $this->checkInEmployeeId;
        } else {
            $employeeId = $employee->id;
        }

        try {
            app(AttendanceService::class)->checkIn($employeeId, 'web', null, null, $user->id);
            $this->checkInEmployeeId = null;
            $this->resetErrorBag('checkInEmployeeId');
            $this->dispatch('attendance-updated');
            $this->resetPage();
        } catch (\DomainException $e) {
            $this->addError('checkIn', $e->getMessage());
        }
    }

    public function checkOut(int $attendanceId): void
    {
        $user = auth()->user();
        $employee = $user->employee;
        if (!$employee) {
            $this->authorize('update attendance');
            $attendance = \App\Modules\Attendance\Models\Attendance::findOrFail($attendanceId);
            $employeeId = $attendance->employee_id;
        } else {
            $employeeId = $employee->id;
        }

        try {
            app(AttendanceService::class)->checkOut($attendanceId, $employeeId, 'web', null, null, $user->id);
            $this->dispatch('attendance-updated');
            $this->resetPage();
        } catch (\DomainException|\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->addError('checkOut', $e->getMessage());
        }
    }

    public function updatedEmployeeId(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDate(): void
    {
        $this->resetPage();
    }

    public function render(AttendanceService $attendanceService): View
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee) {
            return $this->redirect(route('ess.attendance'), navigate: true);
        }

        $this->authorize('view attendance');

        $attendances = $attendanceService->listAttendance([
            'employee_id' => $this->employeeId ?: null,
            'status' => $this->status ?: null,
            'date' => $this->filterDate ?: null,
        ], 25, $this->getPage());

        return view('livewire.attendance.attendance-index-page', [
            'attendances' => $attendances,
        ]);
    }
}
