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
    public ?string $departmentId = null;
    public ?string $status = null;
    public ?string $filterDate = null;

    /** For admin quick check-in: selected employee to check in */
    public ?string $checkInEmployeeId = null;

    /** Max check-in time (H:i) – after this time check-in counts as late */
    public string $maxCheckInTime = '';

    protected $queryString = [
        'employeeId' => ['except' => ''],
        'departmentId' => ['except' => ''],
        'status' => ['except' => ''],
        'filterDate' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->maxCheckInTime = AttendanceSetting::getMaxCheckInTime();
    }

    public function saveMaxCheckInTime(): void
    {
        if (! auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }
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

    public function getDepartmentsProperty()
    {
        return \App\Modules\Employee\Models\Department::orderBy('name')->get();
    }

    public function exportCsv(AttendanceService $attendanceService)
    {
        $this->authorize('view attendance');

        $filters = [
            'employee_id' => $this->employeeId ?: null,
            'department_id' => $this->departmentId ?: null,
            'status' => $this->status ?: null,
            'date' => $this->filterDate ?: null,
        ];

        // Fetch all attendance records with filters
        $query = \App\Modules\Attendance\Models\Attendance::with('employee.department');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        if (!empty($filters['department_id'])) {
            $query->whereHas('employee', function ($q) use ($filters) {
                $q->where('department_id', $filters['department_id']);
            });
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }

        $attendances = $query->get();

        // Group by employee_id and date to avoid duplicates (one record per employee per day)
        // Take the latest record for each employee-date combination
        $grouped = $attendances->groupBy(function ($attendance) {
            return $attendance->employee_id . '_' . $attendance->date->format('Y-m-d');
        })->map(function ($group) {
            // If multiple records exist for same employee-date, take the one with check_out_time or latest
            return $group->sortByDesc('check_out_time')->first();
        });

        // Calculate date range for working days calculation
        $dateRange = $attendances->pluck('date')->unique()->sort();
        $startDate = $dateRange->first();
        $endDate = $dateRange->last();
        
        // Calculate total working days (excluding weekends - Saturday=6, Sunday=0)
        $totalWorkingDays = 0;
        if ($startDate && $endDate) {
            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                // Count weekdays (Monday=1 to Friday=5)
                if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                    $totalWorkingDays++;
                }
                $current->addDay();
            }
        }

        // Group by employee to calculate summary
        $employeeSummary = [];
        foreach ($grouped as $attendance) {
            $employeeId = $attendance->employee_id;
            
            if (!isset($employeeSummary[$employeeId])) {
                $employeeSummary[$employeeId] = [
                    'employee' => $attendance->employee,
                    'present_days' => 0,
                    'total_days' => 0,
                ];
            }
            
            $employeeSummary[$employeeId]['total_days']++;
            if ($attendance->status === 'present') {
                $employeeSummary[$employeeId]['present_days']++;
            }
        }

        $filename = "attendance_export_" . now()->format('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($grouped, $employeeSummary, $totalWorkingDays) {
            $file = fopen('php://output', 'w');
            
            // Write summary header
            fputcsv($file, ['Employee Summary']);
            fputcsv($file, ['Employee ID', 'Employee Name', 'Department', 'Total Present Days', 'Total Working Days', 'Attendance Percentage']);
            
            // Write employee summary
            foreach ($employeeSummary as $summary) {
                $percentage = $totalWorkingDays > 0 
                    ? round(($summary['present_days'] / $totalWorkingDays) * 100, 2) 
                    : 0;
                    
                fputcsv($file, [
                    $summary['employee']->employee_id ?? '-',
                    $summary['employee']->full_name,
                    $summary['employee']->department->name ?? '-',
                    $summary['present_days'],
                    $totalWorkingDays,
                    $percentage . '%'
                ]);
            }
            
            // Empty row separator
            fputcsv($file, []);
            fputcsv($file, ['Detailed Attendance Records (No Duplicates)']);
            fputcsv($file, ['Employee ID', 'Employee Name', 'Department', 'Date', 'Check In', 'Check Out', 'Total Hours', 'Status']);

            // Write detailed records (one per employee per day - no duplicates)
            foreach ($grouped as $attendance) {
                fputcsv($file, [
                    $attendance->employee->employee_id ?? '-',
                    $attendance->employee->full_name,
                    $attendance->employee->department->name ?? '-',
                    $attendance->date->format('Y-m-d'),
                    $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-',
                    $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-',
                    $attendance->total_hours ? round($attendance->total_hours / 60, 2) : '-',
                    ucfirst($attendance->status)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        } catch (\DomainException | \Illuminate\Auth\Access\AuthorizationException $e) {
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

    public function updatedDepartmentId(): void
    {
        $this->resetPage();
    }

    public function render(AttendanceService $attendanceService): View
    {
        $this->authorize('view attendance');

        $attendances = $attendanceService->listAttendance([
            'employee_id' => $this->employeeId ?: null,
            'department_id' => $this->departmentId ?: null,
            'status' => $this->status ?: null,
            'date' => $this->filterDate ?: null,
        ], 25, $this->getPage());

        return view('livewire.attendance.attendance-index-page', [
            'attendances' => $attendances,
        ]);
    }
}
