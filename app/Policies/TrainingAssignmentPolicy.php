<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Training\Models\TrainingAssignment;

class TrainingAssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view training') || $user->employee !== null;
    }

    public function view(User $user, TrainingAssignment $assignment): bool
    {
        if ($user->employee && $user->employee->id === $assignment->employee_id) {
            return true;
        }
        return $user->can('view training');
    }

    public function create(User $user): bool
    {
        return $user->can('manage training');
    }

    public function update(User $user, TrainingAssignment $assignment): bool
    {
        return $user->can('manage training') || ($user->employee && $user->employee->id === $assignment->employee_id);
    }
}
