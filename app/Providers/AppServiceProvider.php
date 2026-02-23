<?php

namespace App\Providers;

use App\Models\Role;
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
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
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

        // Sync Spatie role from designation on login: if user's employee has a designation, assign the role with the same name (if it exists).
        // This way permissions set on the role (e.g. HR Manager) apply to users whose designation is that role name.
        Event::listen(Login::class, function (Login $event): void {
            $user = $event->user;
            if (! $user->relationLoaded('employee')) {
                $user->load('employee.designation');
            }
            $designation = $user->employee?->designation;
            if (! $designation) {
                return;
            }
            $role = Role::where('name', $designation->name)->where('guard_name', 'web')->first();
            if ($role && ! $user->hasRole($role->name)) {
                $user->assignRole($role);
            }
        });

        // Super Admin always has access to everything
        // Then check designation permissions: user can do something if their employee's designation has that permission
        Gate::before(function (?object $user, string $ability) {
            if (! $user) {
                return null;
            }
            // Super Admin bypasses all permission checks
            if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
                return true;
            }
            // Check designation permissions for non-Super Admin users
            if (! method_exists($user, 'employee')) {
                return null;
            }
            $employee = $user->employee;
            if (! $employee?->designation) {
                return null;
            }
            $designation = $employee->designation;
            $designation->loadMissing('permissions');
            if ($designation->hasPermissionTo($ability)) {
                return true;
            }
            return null;
        });

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

        view()->composer(['layouts.partials.sidebar-admin', 'layouts.partials.sidebar-ess', 'layouts.partials.navbar'], function ($view) {
            $sidebarColor = '#343a40';
            $currentPortal = null;
            $availablePortals = [];
            $portalService = null;
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->employee && $user->employee->designation && $user->employee->designation->sidebar_color) {
                    $sidebarColor = $user->employee->designation->sidebar_color;
                } elseif ($user->hasRole('Super Admin')) {
                    $sidebarColor = '#001f3f';
                }
                $portalService = app(\App\Services\PortalService::class);
                $availablePortals = $portalService->getAvailablePortalsForUser($user);
                $currentPortal = session('portal');
            }
            $view->with(compact('sidebarColor', 'currentPortal', 'availablePortals', 'portalService'));
        });
    }
}
