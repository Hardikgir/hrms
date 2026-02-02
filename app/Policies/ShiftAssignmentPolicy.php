<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Shift\Models\ShiftAssignment;

class ShiftAssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view shifts') || $user->employee !== null;
    }

    public function view(User $user, ShiftAssignment $assignment): bool
    {
        if ($user->employee && $user->employee->id === $assignment->employee_id) {
            return true;
        }
        return $user->can('view shifts');
    }

    public function create(User $user): bool
    {
        return $user->can('manage shifts');
    }

    public function delete(User $user, ShiftAssignment $assignment): bool
    {
        return $user->can('manage shifts');
    }
}
