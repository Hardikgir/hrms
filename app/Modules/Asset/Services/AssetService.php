<?php

namespace App\Modules\Asset\Services;

use App\Modules\Asset\Models\Asset;
use App\Modules\Asset\Models\AssetAssignmentHistory;
use App\Modules\Asset\Models\AssetReturnRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssetService
{
    public function list(?int $employeeId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = Asset::with(['employee', 'createdBy', 'returnRequests'])->latest();
        if ($employeeId !== null) {
            $query->where('employee_id', $employeeId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        return $query->paginate(15);
    }

    public function create(array $data, ?int $createdBy = null): Asset
    {
        $data['created_by'] = $createdBy;
        $data['status'] = $data['status'] ?? Asset::STATUS_AVAILABLE;
        return Asset::create($data);
    }

    public function update(Asset $asset, array $data): Asset
    {
        $asset->update($data);
        return $asset->fresh();
    }

    public function assign(Asset $asset, int $employeeId, ?int $assignedBy = null): Asset
    {
        if ($asset->status !== Asset::STATUS_AVAILABLE) {
            throw new \DomainException('Only available assets can be assigned.');
        }
        $asset->update([
            'employee_id' => $employeeId,
            'status' => Asset::STATUS_ASSIGNED,
        ]);
        AssetAssignmentHistory::create([
            'asset_id' => $asset->id,
            'employee_id' => $employeeId,
            'assigned_at' => now(),
            'assigned_by' => $assignedBy,
        ]);
        return $asset->fresh();
    }

    public function unassign(Asset $asset, ?int $returnedBy = null): Asset
    {
        $open = AssetAssignmentHistory::where('asset_id', $asset->id)->whereNull('returned_at')->first();
        if ($open) {
            $open->update([
                'returned_at' => now(),
                'returned_by' => $returnedBy,
            ]);
        }
        $asset->update([
            'employee_id' => null,
            'status' => Asset::STATUS_AVAILABLE,
        ]);
        return $asset->fresh();
    }

    public function requestReturn(Asset $asset, int $employeeId): AssetReturnRequest
    {
        if ($asset->employee_id != $employeeId) {
            throw new \DomainException('Only the assigned employee can request return.');
        }
        if ($asset->pendingReturnRequest()) {
            throw new \DomainException('A return request is already pending.');
        }
        return AssetReturnRequest::create([
            'asset_id' => $asset->id,
            'employee_id' => $employeeId,
            'status' => AssetReturnRequest::STATUS_PENDING,
        ]);
    }

    public function approveReturn(AssetReturnRequest $returnRequest, int $reviewedBy, ?string $adminNote = null): Asset
    {
        if (!$returnRequest->isPending()) {
            throw new \DomainException('Only pending requests can be approved.');
        }
        $returnRequest->update([
            'status' => AssetReturnRequest::STATUS_APPROVED,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
            'admin_note' => $adminNote,
        ]);
        $this->unassign($returnRequest->asset, $reviewedBy);
        return $returnRequest->asset->fresh();
    }

    public function declineReturn(AssetReturnRequest $returnRequest, string $adminNote, int $reviewedBy): AssetReturnRequest
    {
        if (!$returnRequest->isPending()) {
            throw new \DomainException('Only pending requests can be declined.');
        }
        $returnRequest->update([
            'status' => AssetReturnRequest::STATUS_DECLINED,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now(),
            'admin_note' => $adminNote,
        ]);
        return $returnRequest->fresh();
    }
}
