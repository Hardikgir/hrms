<?php

namespace App\Modules\Asset\Services;

use App\Modules\Asset\Models\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssetService
{
    public function list(?int $employeeId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = Asset::with(['employee', 'createdBy'])->latest();
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

    public function assign(Asset $asset, int $employeeId): Asset
    {
        if ($asset->status !== Asset::STATUS_AVAILABLE) {
            throw new \DomainException('Only available assets can be assigned.');
        }
        $asset->update([
            'employee_id' => $employeeId,
            'status' => Asset::STATUS_ASSIGNED,
        ]);
        return $asset->fresh();
    }

    public function unassign(Asset $asset): Asset
    {
        $asset->update([
            'employee_id' => null,
            'status' => Asset::STATUS_AVAILABLE,
        ]);
        return $asset->fresh();
    }
}
