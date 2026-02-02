<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Expense\Models\Expense;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view expenses') || $user->employee !== null;
    }

    public function view(User $user, Expense $expense): bool
    {
        if ($user->employee && $user->employee->id === $expense->employee_id) {
            return true;
        }
        return $user->can('view expenses');
    }

    public function create(User $user): bool
    {
        return $user->employee !== null;
    }

    public function approve(User $user, Expense $expense): bool
    {
        return $user->can('approve expenses');
    }

    public function reimburse(User $user, Expense $expense): bool
    {
        return $user->can('process reimbursements');
    }
}
