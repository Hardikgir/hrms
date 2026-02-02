<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Leave\Models\Leave;

class LeavePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view leaves') || $user->employee !== null;
    }

    public function view(User $user, Leave $leave): bool
    {
        if ($user->employee && $user->employee->id === $leave->employee_id) {
            return true;
        }
        return $user->can('view leaves');
    }

    public function create(User $user): bool
    {
        return $user->can('create leaves') || $user->employee !== null;
    }

    public function update(User $user, Leave $leave): bool
    {
        if ($user->employee && $user->employee->id === $leave->employee_id) {
            return $leave->status === 'pending';
        }
        return $user->can('update leaves');
    }

    public function delete(User $user, Leave $leave): bool
    {
        if ($user->employee && $user->employee->id === $leave->employee_id) {
            return $leave->status === 'pending';
        }
        return $user->can('delete leaves');
    }

    public function approve(User $user, Leave $leave): bool
    {
        return $user->can('approve leaves');
    }
}
