<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Travel\Models\TravelRequest;

class TravelRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view travel') || $user->employee !== null;
    }

    public function view(User $user, TravelRequest $travel): bool
    {
        if ($user->employee && $user->employee->id === $travel->employee_id) {
            return true;
        }
        return $user->can('view travel');
    }

    public function create(User $user): bool
    {
        return $user->employee !== null;
    }

    public function approve(User $user, TravelRequest $travel): bool
    {
        return $user->can('approve travel');
    }
}
