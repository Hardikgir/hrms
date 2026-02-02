<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Shift\Models\Shift;

class ShiftPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view shifts');
    }

    public function view(User $user, Shift $shift): bool
    {
        return $user->can('view shifts');
    }

    public function create(User $user): bool
    {
        return $user->can('manage shifts');
    }

    public function update(User $user, Shift $shift): bool
    {
        return $user->can('manage shifts');
    }

    public function delete(User $user, Shift $shift): bool
    {
        return $user->can('manage shifts');
    }
}
