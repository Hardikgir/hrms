<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Performance\Models\PerformanceReview;

class PerformanceReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view performance') || $user->employee !== null;
    }

    public function view(User $user, PerformanceReview $review): bool
    {
        if ($user->employee && $user->employee->id === $review->employee_id) {
            return true;
        }
        if ($review->reviewer_id === $user->id) {
            return true;
        }
        return $user->can('view performance');
    }

    public function create(User $user): bool
    {
        return $user->can('manage performance');
    }

    public function update(User $user, PerformanceReview $review): bool
    {
        return $user->can('manage performance');
    }

    public function delete(User $user, PerformanceReview $review): bool
    {
        return $user->can('manage performance') && $review->isPending();
    }

    public function submitSelfReview(User $user, PerformanceReview $review): bool
    {
        return $user->employee && $user->employee->id === $review->employee_id && $review->canSubmitSelfReview();
    }

    public function submitManagerReview(User $user, PerformanceReview $review): bool
    {
        return $review->reviewer_id === $user->id && $review->canSubmitManagerReview();
    }
}
