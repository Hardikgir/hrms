<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Performance\Models\Goal;

class GoalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view performance') || $user->employee !== null;
    }

    public function view(User $user, Goal $goal): bool
    {
        if ($user->employee && $user->employee->id === $goal->employee_id) {
            return true;
        }
        return $user->can('view performance');
    }

    public function create(User $user): bool
    {
        return $user->can('manage performance');
    }

    public function update(User $user, Goal $goal): bool
    {
        return $user->can('manage performance');
    }

    public function delete(User $user, Goal $goal): bool
    {
        return $user->can('manage performance');
    }
}
