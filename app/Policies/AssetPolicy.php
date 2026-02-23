<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Asset\Models\Asset;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view assets') || $user->employee !== null;
    }

    public function view(User $user, Asset $asset): bool
    {
        if ($user->employee && $user->employee->id === $asset->employee_id) {
            return true;
        }
        return $user->can('view assets');
    }

    public function create(User $user): bool
    {
        return $user->can('manage assets');
    }

    public function update(User $user, Asset $asset): bool
    {
        return $user->can('manage assets');
    }

    public function approveReturn(User $user, Asset $asset): bool
    {
        return $user->can('approve asset returns');
    }
}
