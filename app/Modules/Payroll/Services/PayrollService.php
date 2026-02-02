<?php

namespace App\Modules\Payroll\Services;

use App\Modules\Attendance\Models\Attendance;
use App\Modules\Employee\Models\Employee;
use App\Modules\Leave\Models\Leave;
use App\Modules\Payroll\Models\Payroll;
use App\Modules\Payroll\Models\PayrollAuditLog;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PayrollService
{
    /**
     * Run payroll for a month: create draft payroll records for all active employees.
     * Uses employee CTC, attendance and approved leaves for the month.
     */
    public function runPayrollForMonth(int $year, int $month, ?int $runBy = null): array
    {
        $start = Carbon::createFromDate($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $workingDays = $start->daysInMonth(); // simplified: all calendar days as working days; can use config

        $employees = Employee::where('is_active', true)
            ->where('employment_status', 'active')
            ->get();

        $created = [];
        foreach ($employees as $employee) {
            $existing = Payroll::where('employee_id', $employee->id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            if ($existing) {
                continue;
            }

            $presentDays = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->whereNotNull('check_out_time')
                ->count();

            $leaveDays = (int) Leave::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $month)
                ->sum('total_days');

            $absentDays = max(0, $workingDays - $presentDays - $leaveDays);

            $ctc = (float) ($employee->ctc ?? 0);
            $basicSalary = $ctc > 0 ? round($ctc / 12, 2) : 0;
            $salaryStructure = $employee->salary_structure;
            if (is_array($salaryStructure)) {
                $earnings = $salaryStructure['earnings'] ?? ['basic' => $basicSalary];
                $deductions = $salaryStructure['deductions'] ?? [];
            } else {
                $earnings = ['basic' => $basicSalary];
                $deductions = [];
            }
            $grossSalary = is_array($earnings) ? array_sum(array_values($earnings)) : $basicSalary;
            $totalDeductions = is_array($deductions) ? array_sum(array_values($deductions)) : 0;
            $netSalary = round($grossSalary - $totalDeductions, 2);

            $payroll = Payroll::create([
                'uuid' => (string) Str::uuid(),
                'employee_id' => $employee->id,
                'year' => $year,
                'month' => $month,
                'pay_period_start' => $start->toDateString(),
                'pay_period_end' => $end->toDateString(),
                'working_days' => $workingDays,
                'present_days' => $presentDays,
                'leave_days' => $leaveDays,
                'absent_days' => $absentDays,
                'basic_salary' => $basicSalary,
                'earnings' => $earnings,
                'deductions' => $deductions,
                'statutory' => $salaryStructure['statutory'] ?? null,
                'gross_salary' => $grossSalary,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'status' => 'draft',
                'created_by' => $runBy,
            ]);

            $this->logAudit($payroll->id, 'run', $runBy, null, 'draft', ['message' => 'Payroll run for month']);
            $created[] = $payroll;
        }

        return $created;
    }

    /**
     * Lock payroll: move from draft to processed (or approved).
     */
    public function lockPayroll(int $payrollId, ?int $userId = null): Payroll
    {
        $payroll = Payroll::findOrFail($payrollId);
        $oldStatus = $payroll->status;
        if ($oldStatus !== 'draft') {
            throw new \DomainException('Only draft payroll can be locked.');
        }
        $payroll->update([
            'status' => 'processed',
            'updated_by' => $userId,
        ]);
        $this->logAudit($payroll->id, 'lock', $userId, $oldStatus, 'processed', ['message' => 'Payroll locked']);
        return $payroll->fresh();
    }

    /**
     * Approve payroll: move to approved.
     */
    public function approvePayroll(int $payrollId, ?int $userId = null): Payroll
    {
        $payroll = Payroll::findOrFail($payrollId);
        $oldStatus = $payroll->status;
        if (!in_array($oldStatus, ['draft', 'processed'], true)) {
            throw new \DomainException('Only draft or processed payroll can be approved.');
        }
        $payroll->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
            'updated_by' => $userId,
        ]);
        $this->logAudit($payroll->id, 'approve', $userId, $oldStatus, 'approved', []);
        return $payroll->fresh();
    }

    /**
     * Mark payroll as paid.
     */
    public function markAsPaid(int $payrollId, ?string $paidDate = null, ?int $userId = null): Payroll
    {
        $payroll = Payroll::findOrFail($payrollId);
        $oldStatus = $payroll->status;
        if ($oldStatus !== 'approved') {
            throw new \DomainException('Only approved payroll can be marked as paid.');
        }
        $payroll->update([
            'status' => 'paid',
            'paid_date' => $paidDate ?? now()->toDateString(),
            'updated_by' => $userId,
        ]);
        $this->logAudit($payroll->id, 'paid', $userId, $oldStatus, 'paid', []);
        return $payroll->fresh();
    }

    public function logAudit(
        int $payrollId,
        string $action,
        ?int $userId,
        ?string $oldStatus,
        ?string $newStatus,
        array $payload = []
    ): PayrollAuditLog {
        return PayrollAuditLog::create([
            'payroll_id' => $payrollId,
            'action' => $action,
            'user_id' => $userId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'payload' => $payload,
        ]);
    }

    /**
     * List payroll with filters.
     *
     * @param  array{year?: int, month?: int, employee_id?: int, status?: string}  $filters
     */
    public function listPayroll(array $filters = [], int $perPage = 20, ?int $page = null): LengthAwarePaginator
    {
        $query = Payroll::with('employee')->latest('year')->latest('month');

        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }
        if (!empty($filters['month'])) {
            $query->where('month', $filters['month']);
        }
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $page = $page ?? request()->query('page', 1);
        return $query->paginate($perPage, ['*'], 'page', (int) $page);
    }

    /**
     * Get audit logs for a payroll.
     */
    public function getAuditLogs(int $payrollId): \Illuminate\Database\Eloquent\Collection
    {
        return PayrollAuditLog::where('payroll_id', $payrollId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
