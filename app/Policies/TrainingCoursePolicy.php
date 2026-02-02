<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Training\Models\TrainingCourse;

class TrainingCoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view training') || $user->employee !== null;
    }

    public function view(User $user, TrainingCourse $course): bool
    {
        return $user->can('view training') || $user->employee !== null;
    }

    public function create(User $user): bool
    {
        return $user->can('manage training');
    }

    public function update(User $user, TrainingCourse $course): bool
    {
        return $user->can('manage training');
    }
}
