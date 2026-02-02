<?php

namespace App\Modules\Exit\Services;

use App\Modules\Employee\Models\Employee;
use App\Modules\Exit\Models\ExitRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExitService
{
    public function list(?int $employeeId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = ExitRequest::with(['employee', 'approvedBy', 'createdBy'])->latest();
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
        string $resignationDate,
        string $lastWorkingDate,
        ?string $reason = null,
        ?string $reasonDetails = null,
        ?int $createdBy = null
    ): ExitRequest {
        $exists = ExitRequest::where('employee_id', $employeeId)
            ->whereIn('status', [ExitRequest::STATUS_PENDING, ExitRequest::STATUS_CLEARANCE, ExitRequest::STATUS_EXIT_INTERVIEW, ExitRequest::STATUS_SETTLEMENT])
            ->exists();
        if ($exists) {
            throw new \DomainException('Employee already has an active exit request.');
        }
        return ExitRequest::create([
            'employee_id' => $employeeId,
            'resignation_date' => $resignationDate,
            'last_working_date' => $lastWorkingDate,
            'reason' => $reason,
            'reason_details' => $reasonDetails,
            'status' => ExitRequest::STATUS_PENDING,
            'checklist' => ['it' => false, 'hr' => false, 'finance' => false, 'assets' => false],
            'created_by' => $createdBy,
        ]);
    }

    public function updateStatus(ExitRequest $request, string $status, array $data = []): ExitRequest
    {
        $request->update(array_merge(['status' => $status], $data));
        return $request->fresh();
    }

    public function updateChecklist(ExitRequest $request, array $checklist): ExitRequest
    {
        $request->update(['checklist' => array_merge($request->checklist ?? [], $checklist)]);
        return $request->fresh();
    }

    public function completeClearance(ExitRequest $request): ExitRequest
    {
        $request->update([
            'status' => ExitRequest::STATUS_EXIT_INTERVIEW,
            'clearance_completed_at' => now(),
        ]);
        return $request->fresh();
    }

    public function recordSettlement(ExitRequest $request, float $amount, ?string $paidAt = null): ExitRequest
    {
        $request->update([
            'status' => ExitRequest::STATUS_COMPLETED,
            'settlement_amount' => $amount,
            'settlement_paid_at' => $paidAt ?? now()->toDateString(),
        ]);
        return $request->fresh();
    }
}
