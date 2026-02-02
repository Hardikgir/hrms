<?php

namespace App\Modules\Leave\Services;

use App\Modules\Leave\Models\Leave;
use App\Modules\Leave\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class LeaveService
{
    /**
     * Apply for leave. Returns the created Leave.
     */
    public function apply(
        int $employeeId,
        int $leaveTypeId,
        string $startDate,
        string $endDate,
        string $reason,
        ?int $createdBy = null
    ): Leave {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        if ($end->lt($start)) {
            throw new \InvalidArgumentException('End date must be on or after start date.');
        }
        $totalDays = $start->diffInDays($end) + 1;

        $leaveType = LeaveType::findOrFail($leaveTypeId);
        if (!$leaveType->is_active) {
            throw new \DomainException('Leave type is not active.');
        }

        // Optional: check balance (can be enforced here)
        $year = (int) $start->format('Y');
        $balance = $this->getBalanceForEmployee($employeeId, $leaveTypeId, $year);
        if ($balance['available'] < $totalDays) {
            throw new \DomainException("Insufficient leave balance. Available: {$balance['available']} days.");
        }

        return Leave::create([
            'uuid' => (string) Str::uuid(),
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'total_days' => $totalDays,
            'reason' => $reason,
            'status' => 'pending',
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Approve a leave request.
     */
    public function approve(int $leaveId, ?int $approvedBy = null): Leave
    {
        $leave = Leave::findOrFail($leaveId);
        if ($leave->status !== 'pending') {
            throw new \DomainException('Only pending leaves can be approved.');
        }
        $leave->update([
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'rejection_reason' => null,
            'rejected_by' => null,
            'rejected_at' => null,
            'updated_by' => $approvedBy,
        ]);
        return $leave->fresh();
    }

    /**
     * Reject a leave request.
     */
    public function reject(int $leaveId, string $rejectionReason, ?int $rejectedBy = null): Leave
    {
        $leave = Leave::findOrFail($leaveId);
        if ($leave->status !== 'pending') {
            throw new \DomainException('Only pending leaves can be rejected.');
        }
        $leave->update([
            'status' => 'rejected',
            'rejection_reason' => $rejectionReason,
            'rejected_by' => $rejectedBy,
            'rejected_at' => now(),
            'approved_by' => null,
            'approved_at' => null,
            'updated_by' => $rejectedBy,
        ]);
        return $leave->fresh();
    }

    /**
     * Cancel a leave (by employee) - only if pending.
     */
    public function cancel(int $leaveId, ?int $updatedBy = null): Leave
    {
        $leave = Leave::findOrFail($leaveId);
        if ($leave->status !== 'pending') {
            throw new \DomainException('Only pending leaves can be cancelled.');
        }
        $leave->update([
            'status' => 'cancelled',
            'updated_by' => $updatedBy,
        ]);
        return $leave->fresh();
    }

    /**
     * List leaves for admin with filters.
     *
     * @param  array{search?: string, status?: string, employee_id?: int}  $filters
     */
    public function listForAdmin(array $filters = [], int $perPage = 20, ?int $page = null): LengthAwarePaginator
    {
        $query = Leave::with(['employee', 'leaveType'])->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        $page = $page ?? request()->query('page', 1);
        return $query->paginate($perPage, ['*'], 'page', (int) $page);
    }

    /**
     * List leaves for an employee.
     */
    public function listForEmployee(int $employeeId, ?string $status = null, int $perPage = 20, ?int $page = null): LengthAwarePaginator
    {
        $query = Leave::with('leaveType')
            ->where('employee_id', $employeeId)
            ->latest();

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        $page = $page ?? request()->query('page', 1);
        return $query->paginate($perPage, ['*'], 'page', (int) $page);
    }

    /**
     * Get leave balance for an employee for a leave type and year.
     * Returns: allocated (from max_days_per_year + carry forward), used (approved days), available.
     */
    public function getBalanceForEmployee(int $employeeId, int $leaveTypeId, int $year): array
    {
        $leaveType = LeaveType::findOrFail($leaveTypeId);
        $maxDays = $leaveType->max_days_per_year ?? 0;
        $carryLimit = $leaveType->can_carry_forward ? ($leaveType->carry_forward_limit ?? 0) : 0;

        $used = (int) Leave::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum('total_days');

        $carryForward = 0;
        if ($leaveType->can_carry_forward && $carryLimit > 0) {
            $prevUsed = (int) Leave::where('employee_id', $employeeId)
                ->where('leave_type_id', $leaveTypeId)
                ->where('status', 'approved')
                ->whereYear('start_date', $year - 1)
                ->sum('total_days');
            $prevAllocated = $leaveType->max_days_per_year ?? 0;
            $carryForward = min($carryLimit, max(0, $prevAllocated - $prevUsed));
        }

        $allocated = $maxDays + $carryForward;
        $available = max(0, $allocated - $used);

        return [
            'allocated' => $allocated,
            'used' => $used,
            'available' => $available,
            'carry_forward' => $carryForward,
        ];
    }

    /**
     * Get balance dashboard for an employee (all active leave types) for a year.
     */
    public function getBalanceDashboardForEmployee(int $employeeId, int $year): array
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        $dashboard = [];
        foreach ($leaveTypes as $type) {
            $dashboard[] = [
                'leave_type' => $type,
                'balance' => $this->getBalanceForEmployee($employeeId, $type->id, $year),
            ];
        }
        return $dashboard;
    }

    /**
     * Calculate total days between two dates (inclusive).
     */
    public static function calculateTotalDays(string $startDate, string $endDate): int
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        return $start->diffInDays($end) + 1;
    }
}
