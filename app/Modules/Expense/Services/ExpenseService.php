<?php

namespace App\Modules\Expense\Services;

use App\Modules\Expense\Models\Expense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ExpenseService
{
    public function list(?int $employeeId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = Expense::with(['employee', 'approvedBy', 'createdBy'])->latest();
        if ($employeeId !== null) {
            $query->where('employee_id', $employeeId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        return $query->paginate(15);
    }

    public function submit(
        int $employeeId,
        float $amount,
        string $category,
        ?string $description = null,
        $receiptFile = null,
        ?int $createdBy = null
    ): Expense {
        $receiptPath = null;
        if ($receiptFile) {
            $receiptPath = $receiptFile->store('expenses/' . $employeeId, 'local');
        }
        return Expense::create([
            'employee_id' => $employeeId,
            'amount' => $amount,
            'category' => $category,
            'description' => $description,
            'receipt_path' => $receiptPath,
            'status' => Expense::STATUS_PENDING,
            'created_by' => $createdBy,
        ]);
    }

    public function approve(int $expenseId, ?int $approvedBy = null): Expense
    {
        $expense = Expense::findOrFail($expenseId);
        if ($expense->status !== Expense::STATUS_PENDING) {
            throw new \DomainException('Only pending expenses can be approved.');
        }
        $approvedAt = now();
        $expense->update([
            'status' => Expense::STATUS_APPROVED,
            'approved_by' => $approvedBy,
            'approved_at' => $approvedAt,
            'rejected_by' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
        $expense = $expense->fresh();

        // Add approved expense to employee's draft payroll for this month (so it is included in salary)
        app(\App\Modules\Payroll\Services\PayrollService::class)->addApprovedExpenseToDraftPayroll(
            $expense->employee_id,
            (int) $approvedAt->format('Y'),
            (int) $approvedAt->format('n'),
            (float) $expense->amount
        );

        return $expense;
    }

    public function reject(int $expenseId, string $reason, ?int $rejectedBy = null): Expense
    {
        $expense = Expense::findOrFail($expenseId);
        if ($expense->status !== Expense::STATUS_PENDING) {
            throw new \DomainException('Only pending expenses can be rejected.');
        }
        $expense->update([
            'status' => Expense::STATUS_REJECTED,
            'rejected_by' => $rejectedBy,
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'approved_by' => null,
            'approved_at' => null,
        ]);
        return $expense->fresh();
    }

    public function markReimbursed(int $expenseId, ?int $reimbursedBy = null): Expense
    {
        $expense = Expense::findOrFail($expenseId);
        if ($expense->status !== Expense::STATUS_APPROVED) {
            throw new \DomainException('Only approved expenses can be marked as reimbursed.');
        }
        $expense->update([
            'status' => Expense::STATUS_REIMBURSED,
            'reimbursed_by' => $reimbursedBy,
            'reimbursed_at' => now(),
        ]);
        return $expense->fresh();
    }
}
