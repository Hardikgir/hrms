<?php

namespace App\Modules\Shift\Services;

use App\Modules\Employee\Models\Employee;
use App\Modules\Shift\Models\Shift;
use App\Modules\Shift\Models\ShiftAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ShiftService
{
    public function listShifts(bool $activeOnly = true)
    {
        $query = Shift::with('assignments')->latest();
        if ($activeOnly) {
            $query->where('is_active', true);
        }
        return $query->paginate(15);
    }

    public function createShift(array $data, ?int $createdBy = null): Shift
    {
        $data['uuid'] = (string) Str::uuid();
        $data['created_by'] = $createdBy;
        return Shift::create($data);
    }

    public function updateShift(Shift $shift, array $data): Shift
    {
        $shift->update($data);
        return $shift->fresh();
    }

    public function getRoster(Carbon $dateFrom, Carbon $dateTo, ?int $employeeId = null): Collection
    {
        $query = ShiftAssignment::with(['employee', 'shift', 'assignedBy'])
            ->whereBetween('assignment_date', [$dateFrom->toDateString(), $dateTo->toDateString()]);
        if ($employeeId !== null) {
            $query->where('employee_id', $employeeId);
        }
        return $query->orderBy('assignment_date')->orderBy('employee_id')->get();
    }

    public function assignShift(int $employeeId, int $shiftId, string $date, ?string $notes = null, ?int $assignedBy = null): ShiftAssignment
    {
        $exists = ShiftAssignment::where('employee_id', $employeeId)->where('assignment_date', $date)->exists();
        if ($exists) {
            throw new \DomainException('Employee already has a shift assigned for this date.');
        }
        return ShiftAssignment::create([
            'employee_id' => $employeeId,
            'shift_id' => $shiftId,
            'assignment_date' => $date,
            'notes' => $notes,
            'assigned_by' => $assignedBy,
        ]);
    }

    public function removeAssignment(ShiftAssignment $assignment): void
    {
        $assignment->delete();
    }

    public function getAssignmentForEmployeeOnDate(int $employeeId, string $date): ?ShiftAssignment
    {
        return ShiftAssignment::with('shift')
            ->where('employee_id', $employeeId)
            ->where('assignment_date', $date)
            ->first();
    }
}
