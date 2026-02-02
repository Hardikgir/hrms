<?php

namespace App\Providers;

use App\Modules\Attendance\Models\Attendance;
use App\Modules\Employee\Models\Employee;
use App\Modules\Leave\Models\Leave;
use App\Modules\Payroll\Models\Payroll;
use App\Policies\AttendancePolicy;
use App\Policies\EmployeePolicy;
use App\Policies\LeavePolicy;
use App\Policies\PayrollPolicy;
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
    }
}
