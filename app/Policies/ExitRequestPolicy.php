<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Exit\Models\ExitRequest;

class ExitRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view exit') || $user->employee !== null;
    }

    public function view(User $user, ExitRequest $exit): bool
    {
        if ($user->employee && $user->employee->id === $exit->employee_id) {
            return true;
        }
        return $user->can('view exit');
    }

    public function create(User $user): bool
    {
        return $user->employee !== null;
    }

    public function update(User $user, ExitRequest $exit): bool
    {
        return $user->can('manage exit');
    }
}
