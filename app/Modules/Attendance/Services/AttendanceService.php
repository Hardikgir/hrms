<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Attendance\Models\Attendance;
use Illuminate\Support\Str;

class AttendanceService
{
    /**
     * Check in an employee. Returns the created Attendance or throws.
     */
    public function checkIn(
        int $employeeId,
        string $method = 'web',
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $userId = null
    ): Attendance {
        $today = now()->toDateString();
        $existing = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            throw new \DomainException('Already checked in today.');
        }

        return Attendance::create([
            'uuid' => (string) Str::uuid(),
            'employee_id' => $employeeId,
            'date' => $today,
            'check_in_time' => now(),
            'check_in_method' => $method,
            'check_in_latitude' => $latitude,
            'check_in_longitude' => $longitude,
            'status' => 'present',
            'created_by' => $userId,
        ]);
    }

    /**
     * Check out an attendance record. Recalculates total_hours.
     */
    public function checkOut(
        int $attendanceId,
        int $employeeId,
        string $method = 'web',
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $userId = null
    ): Attendance {
        $attendance = Attendance::findOrFail($attendanceId);

        if ($attendance->employee_id !== $employeeId) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Unauthorized access.');
        }

        if ($attendance->check_out_time) {
            throw new \DomainException('Already checked out today.');
        }

        $checkOut = now();
        $totalMinutes = $attendance->check_in_time->diffInMinutes($checkOut);

        $attendance->update([
            'check_out_time' => $checkOut,
            'check_out_method' => $method,
            'check_out_latitude' => $latitude,
            'check_out_longitude' => $longitude,
            'total_hours' => $totalMinutes,
            'updated_by' => $userId,
        ]);

        return $attendance->fresh();
    }

    /**
     * Get today's attendance for an employee.
     */
    public function getTodayAttendanceForEmployee(int $employeeId): ?Attendance
    {
        return Attendance::where('employee_id', $employeeId)
            ->whereDate('date', now()->toDateString())
            ->first();
    }

    /**
     * List attendance with filters (for admin).
     *
     * @param  array{employee_id?: int, status?: string, date?: string, month?: int, month_year?: int}  $filters
     * @param  int  $perPage
     * @param  int|null  $page  Current page (for Livewire pagination)
     */
    public function listAttendance(array $filters = [], int $perPage = 25, ?int $page = null)
    {
        $query = Attendance::with('employee')->latest('date');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }
        if (!empty($filters['month'])) {
            $query->whereMonth('date', $filters['month'])->whereYear('date', $filters['month_year'] ?? now()->year);
        }

        $page = $page ?? request()->query('page', 1);

        return $query->paginate($perPage, ['*'], 'page', (int) $page);
    }
}
