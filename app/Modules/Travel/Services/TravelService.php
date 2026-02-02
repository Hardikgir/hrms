<?php

namespace App\Modules\Travel\Services;

use App\Modules\Travel\Models\TravelRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TravelService
{
    public function list(?int $employeeId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = TravelRequest::with(['employee', 'approvedBy', 'createdBy'])->latest();
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
        string $purpose,
        string $startDate,
        string $endDate,
        ?string $destination = null,
        ?float $estimatedAmount = null,
        ?string $notes = null,
        ?int $createdBy = null
    ): TravelRequest {
        return TravelRequest::create([
            'employee_id' => $employeeId,
            'purpose' => $purpose,
            'destination' => $destination,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => TravelRequest::STATUS_PENDING,
            'estimated_amount' => $estimatedAmount,
            'notes' => $notes,
            'created_by' => $createdBy,
        ]);
    }

    public function approve(int $requestId, ?int $approvedBy = null): TravelRequest
    {
        $req = TravelRequest::findOrFail($requestId);
        if ($req->status !== TravelRequest::STATUS_PENDING) {
            throw new \DomainException('Only pending requests can be approved.');
        }
        $req->update([
            'status' => TravelRequest::STATUS_APPROVED,
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
        return $req->fresh();
    }

    public function reject(int $requestId, string $reason, ?int $rejectedBy = null): TravelRequest
    {
        $req = TravelRequest::findOrFail($requestId);
        if ($req->status !== TravelRequest::STATUS_PENDING) {
            throw new \DomainException('Only pending requests can be rejected.');
        }
        $req->update([
            'status' => TravelRequest::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);
        return $req->fresh();
    }

    public function markCompleted(int $requestId, ?float $actualAmount = null): TravelRequest
    {
        $req = TravelRequest::findOrFail($requestId);
        if ($req->status !== TravelRequest::STATUS_APPROVED) {
            throw new \DomainException('Only approved requests can be marked completed.');
        }
        $req->update([
            'status' => TravelRequest::STATUS_COMPLETED,
            'actual_amount' => $actualAmount ?? $req->estimated_amount,
        ]);
        return $req->fresh();
    }
}
