<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Performance\Models\PerformanceReviewCycle;

class PerformanceReviewCyclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view performance');
    }

    public function view(User $user, PerformanceReviewCycle $cycle): bool
    {
        return $user->can('view performance');
    }

    public function create(User $user): bool
    {
        return $user->can('manage performance');
    }

    public function update(User $user, PerformanceReviewCycle $cycle): bool
    {
        return $user->can('manage performance');
    }

    public function delete(User $user, PerformanceReviewCycle $cycle): bool
    {
        return $user->can('manage performance') && $cycle->isDraft();
    }
}
