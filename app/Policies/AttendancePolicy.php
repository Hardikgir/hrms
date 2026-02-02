<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Attendance\Models\Attendance;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view attendance');
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->employee && $user->employee->id === $attendance->employee_id) {
            return true;
        }
        return $user->can('view attendance');
    }

    public function create(User $user): bool
    {
        return $user->can('create attendance') || $user->employee !== null;
    }

    public function update(User $user, Attendance $attendance): bool
    {
        if ($user->employee && $user->employee->id === $attendance->employee_id) {
            return true;
        }
        return $user->can('update attendance');
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->can('delete attendance');
    }
}
