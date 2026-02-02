<?php

namespace App\Providers;

use App\Modules\Asset\Models\Asset;
use App\Modules\Attendance\Models\Attendance;
use App\Modules\Employee\Models\Employee;
use App\Modules\Expense\Models\Expense;
use App\Modules\Exit\Models\ExitRequest;
use App\Modules\Leave\Models\Leave;
use App\Modules\Performance\Models\Goal;
use App\Modules\Performance\Models\PerformanceReview;
use App\Modules\Performance\Models\PerformanceReviewCycle;
use App\Modules\Payroll\Models\Payroll;
use App\Modules\Shift\Models\Shift;
use App\Modules\Shift\Models\ShiftAssignment;
use App\Modules\Training\Models\TrainingAssignment;
use App\Modules\Training\Models\TrainingCourse;
use App\Modules\Travel\Models\TravelRequest;
use App\Policies\AssetPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\EmployeePolicy;
use App\Policies\ExpensePolicy;
use App\Policies\ExitRequestPolicy;
use App\Policies\GoalPolicy;
use App\Policies\LeavePolicy;
use App\Policies\PerformanceReviewCyclePolicy;
use App\Policies\PerformanceReviewPolicy;
use App\Policies\PayrollPolicy;
use App\Policies\ShiftAssignmentPolicy;
use App\Policies\ShiftPolicy;
use App\Policies\TrainingAssignmentPolicy;
use App\Policies\TrainingCoursePolicy;
use App\Policies\TravelRequestPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);
        Gate::policy(Leave::class, LeavePolicy::class);
        Gate::policy(Payroll::class, PayrollPolicy::class);
        Gate::policy(PerformanceReviewCycle::class, PerformanceReviewCyclePolicy::class);
        Gate::policy(Goal::class, GoalPolicy::class);
        Gate::policy(PerformanceReview::class, PerformanceReviewPolicy::class);
        Gate::policy(Expense::class, ExpensePolicy::class);
        Gate::policy(TrainingCourse::class, TrainingCoursePolicy::class);
        Gate::policy(TrainingAssignment::class, TrainingAssignmentPolicy::class);
        Gate::policy(Shift::class, ShiftPolicy::class);
        Gate::policy(ShiftAssignment::class, ShiftAssignmentPolicy::class);
        Gate::policy(Asset::class, AssetPolicy::class);
        Gate::policy(TravelRequest::class, TravelRequestPolicy::class);
        Gate::policy(ExitRequest::class, ExitRequestPolicy::class);
    }
}
