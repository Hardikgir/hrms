<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Employee\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect()->route('portal.select');
});

Route::get('/language/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');

    // Role Management
    Route::middleware('can:manage roles')->group(function () {
        Route::post('roles/create-for-department', [\App\Http\Controllers\RoleController::class, 'createForDepartment'])->name('roles.create-for-department');
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->except(['show']);
        Route::get('user-roles', [\App\Http\Controllers\UserRoleController::class, 'index'])->name('user-roles.index');
        Route::get('user-roles/{user}/edit', [\App\Http\Controllers\UserRoleController::class, 'edit'])->name('user-roles.edit');
        Route::put('user-roles/{user}', [\App\Http\Controllers\UserRoleController::class, 'update'])->name('user-roles.update');
    });
    Route::get('/dashboard', function () {
        return redirect()->route('portal.select');
    })->name('dashboard');

    // Portal Selection
    Route::get('/portal', [\App\Http\Controllers\PortalController::class, 'select'])->name('portal.select');
    Route::post('/portal', [\App\Http\Controllers\PortalController::class, 'enter'])->name('portal.enter');

    // Employee Self Service (ESS) Routes
    Route::prefix('ess')->name('ess.')->group(function () {
        Route::get('/dashboard', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'updateProfile'])->name('profile.update');
        Route::get('/tasks', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'tasks'])->name('tasks');
        Route::post('/tasks/{task}/complete', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'completeTask'])->name('tasks.complete');
        Route::get('/onboarding-documents', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'onboardingDocuments'])->name('onboarding-documents');
        Route::post('/onboarding-documents', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'submitOnboardingDocuments'])->name('onboarding-documents.submit');
        Route::get('/training-session', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'trainingSession'])->name('training-session');
        Route::post('/training-session/confirm', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'confirmTrainingAttendance'])->name('training-session.confirm');
        Route::get('/attendance', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'attendance'])->name('attendance');
        Route::get('/leaves', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'leaves'])->name('leaves');
        Route::get('/leaves/create', [\App\Modules\Leave\Controllers\LeaveController::class, 'create'])->name('leaves.create');
        Route::get('/leaves/{leave}', [\App\Modules\Leave\Controllers\LeaveController::class, 'show'])->name('leaves.show');
        Route::get('/leaves/{leave}/edit', [\App\Modules\Leave\Controllers\LeaveController::class, 'edit'])->name('leaves.edit');
        Route::put('/leaves/{leave}', [\App\Modules\Leave\Controllers\LeaveController::class, 'update'])->name('leaves.update');
        Route::post('/leaves', [\App\Modules\Leave\Controllers\LeaveController::class, 'store'])->name('leaves.store');
        Route::get('/payslips', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'payslips'])->name('payslips');
        Route::get('/payslips/{payroll}', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'viewPayslip'])->name('payslips.show');
        Route::get('/goals', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'goals'])->name('goals');
        Route::get('/reviews', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'reviews'])->name('reviews');
        Route::get('/expenses', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'expenses'])->name('expenses');
        Route::get('/expenses/create', [\App\Modules\Expense\Controllers\ExpenseController::class, 'create'])->name('expenses.create');
        Route::get('/expenses/{expense}', [\App\Modules\Expense\Controllers\ExpenseController::class, 'show'])->name('expenses.show');
        Route::post('/expenses', [\App\Modules\Expense\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('/training', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'training'])->name('training');
        Route::get('/roster', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'roster'])->name('roster');
        Route::get('/assets', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'assets'])->name('assets');
        Route::post('/assets/{asset}/request-return', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'requestAssetReturn'])->name('assets.request-return');
        Route::get('/travel', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'travel'])->name('travel');
        Route::get('/travel/create', [\App\Modules\Travel\Controllers\TravelRequestController::class, 'create'])->name('travel.create');
        Route::get('/travel/{travel}', [\App\Modules\Travel\Controllers\TravelRequestController::class, 'show'])->name('travel.show');
        Route::post('/travel', [\App\Modules\Travel\Controllers\TravelRequestController::class, 'store'])->name('travel.store');
        Route::get('/exit', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'exit'])->name('exit');
        Route::get('/exit/create', [\App\Modules\Exit\Controllers\ExitRequestController::class, 'create'])->name('exit.create');
        Route::post('/exit', [\App\Modules\Exit\Controllers\ExitRequestController::class, 'store'])->name('exit.store');
    });

    // Admin/HR Routes (only for non-employees or with permissions)
    Route::middleware(['can:view employees'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('dashboard');
        })->name('admin.dashboard');

        // Employee Routes
        Route::get('employees/{employee}/documents/{document}/download', [EmployeeController::class, 'downloadDocument'])->name('employees.documents.download');
        Route::get('employees/{employee}/documents/{document}/view', [EmployeeController::class, 'viewDocument'])->name('employees.documents.view');
        Route::resource('employees', EmployeeController::class);
    });

    // Employee Tasks (Admin: create/assign tasks for ESS)
    Route::post('employee-tasks/{employee_task}/approve', [\App\Modules\Employee\Controllers\EmployeeTaskController::class, 'approve'])->name('employee-tasks.approve')->middleware('can:manage tasks');
    Route::resource('employee-tasks', \App\Modules\Employee\Controllers\EmployeeTaskController::class)->except(['show'])->middleware('can:manage tasks');

    // Attendance Routes - Admin can view all, employees can check in/out
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [\App\Modules\Attendance\Controllers\AttendanceController::class, 'index'])->name('index')->middleware('can:view attendance');
        Route::post('/check-in', [\App\Modules\Attendance\Controllers\AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [\App\Modules\Attendance\Controllers\AttendanceController::class, 'checkOut'])->name('check-out');
    });

    // Leave Routes - Employees can create/view their own, admins can view all
    Route::resource('leaves', \App\Modules\Leave\Controllers\LeaveController::class);

    // Payroll Routes - Only admins can access
    Route::prefix('payroll')->name('payroll.')->middleware('can:view payroll')->group(function () {
        Route::get('/', [\App\Modules\Payroll\Controllers\PayrollController::class, 'index'])->name('index');
        Route::get('/run', [\App\Modules\Payroll\Controllers\PayrollController::class, 'run'])->name('run');
        Route::get('/{payroll}', [\App\Modules\Payroll\Controllers\PayrollController::class, 'show'])->name('show');
        Route::post('/{payroll}/lock', [\App\Modules\Payroll\Controllers\PayrollController::class, 'lock'])->name('lock');
        Route::post('/{payroll}/approve', [\App\Modules\Payroll\Controllers\PayrollController::class, 'approve'])->name('approve');
    });

    // Expense Management
    Route::get('expenses/{expense}/receipt', [\App\Modules\Expense\Controllers\ExpenseController::class, 'downloadReceipt'])->name('expenses.receipt');
    Route::resource('expenses', \App\Modules\Expense\Controllers\ExpenseController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('expense-categories', \App\Modules\Expense\Controllers\ExpenseCategoryController::class)->except(['show'])->middleware('can:manage expense categories');
    Route::post('expenses/{expense}/approve', [\App\Modules\Expense\Controllers\ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [\App\Modules\Expense\Controllers\ExpenseController::class, 'reject'])->name('expenses.reject');
    Route::post('expenses/{expense}/reimburse', [\App\Modules\Expense\Controllers\ExpenseController::class, 'reimburse'])->name('expenses.reimburse');

    // Training & Development
    Route::prefix('training')->name('training.')->middleware('can:view training')->group(function () {
        Route::resource('courses', \App\Modules\Training\Controllers\TrainingCourseController::class)->except(['show', 'destroy']);
        Route::resource('assignments', \App\Modules\Training\Controllers\TrainingAssignmentController::class)->only(['index', 'create', 'store']);
        Route::post('assignments/{training_assignment}/complete', [\App\Modules\Training\Controllers\TrainingAssignmentController::class, 'complete'])->name('assignments.complete');
    });

    // Shift & Scheduling
    Route::middleware('can:view shifts')->group(function () {
        Route::resource('shifts', \App\Modules\Shift\Controllers\ShiftController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('roster', [\App\Modules\Shift\Controllers\RosterController::class, 'index'])->name('roster.index');
        Route::post('roster', [\App\Modules\Shift\Controllers\RosterController::class, 'store'])->name('roster.store');
        Route::delete('roster/{roster}', [\App\Modules\Shift\Controllers\RosterController::class, 'destroy'])->name('roster.destroy');
    });

    // Asset Management
    Route::resource('assets', \App\Modules\Asset\Controllers\AssetController::class)->except(['show']);
    Route::get('assets/{asset}/history', [\App\Modules\Asset\Controllers\AssetController::class, 'history'])->name('assets.history');
    Route::post('assets/{asset}/assign', [\App\Modules\Asset\Controllers\AssetController::class, 'assign'])->name('assets.assign');
    Route::post('assets/{asset}/unassign', [\App\Modules\Asset\Controllers\AssetController::class, 'unassign'])->name('assets.unassign');
    Route::post('assets/return-requests/{asset_return_request}/approve', [\App\Modules\Asset\Controllers\AssetController::class, 'approveReturn'])->name('assets.return-requests.approve');
    Route::post('assets/return-requests/{asset_return_request}/decline', [\App\Modules\Asset\Controllers\AssetController::class, 'declineReturn'])->name('assets.return-requests.decline');
    Route::resource('asset-types', \App\Modules\Asset\Controllers\AssetTypeController::class)->except(['show'])->parameters(['asset-types' => 'asset_type'])->middleware('can:manage asset types');

    // Employment Types & Statuses Management
    // Employee Management
    Route::resource('employees', \App\Modules\Employee\Controllers\EmployeeController::class);
    Route::get('employees/{employee}/documents/{document}/download', [\App\Modules\Employee\Controllers\EmployeeController::class, 'downloadDocument'])->name('employees.documents.download');
    Route::get('employees/{employee}/documents/{document}/view', [\App\Modules\Employee\Controllers\EmployeeController::class, 'viewDocument'])->name('employees.documents.view');
    Route::resource('employment-types', \App\Modules\Employee\Controllers\EmploymentTypeController::class)->except(['show'])->parameters(['employment-types' => 'employment_type'])->middleware('can:manage employment types');
    Route::resource('employment-statuses', \App\Modules\Employee\Controllers\EmploymentStatusController::class)->except(['show'])->parameters(['employment-statuses' => 'employment_status'])->middleware('can:manage employment statuses');

    // Leave Types Management
    Route::resource('leave-types', \App\Modules\Leave\Controllers\LeaveTypeController::class)->except(['show']);

    // Job Details Dropdowns (Departments, Designations, Locations)
    Route::resource('departments', \App\Modules\Employee\Controllers\DepartmentController::class)->except(['show'])->middleware('can:manage departments');
    Route::resource('designations', \App\Modules\Employee\Controllers\DesignationController::class)->except(['show'])->middleware('can:manage designations');
    Route::resource('locations', \App\Modules\Employee\Controllers\LocationController::class)->except(['show'])->middleware('can:manage locations');

    // Travel Management
    Route::resource('travel', \App\Modules\Travel\Controllers\TravelRequestController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('travel/{travel}/approve', [\App\Modules\Travel\Controllers\TravelRequestController::class, 'approve'])->name('travel.approve');
    Route::post('travel/{travel}/reject', [\App\Modules\Travel\Controllers\TravelRequestController::class, 'reject'])->name('travel.reject');
    Route::post('travel/{travel}/complete', [\App\Modules\Travel\Controllers\TravelRequestController::class, 'complete'])->name('travel.complete');

    // Exit Management
    Route::resource('exit', \App\Modules\Exit\Controllers\ExitRequestController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('exit/{exit}/status', [\App\Modules\Exit\Controllers\ExitRequestController::class, 'updateStatus'])->name('exit.status');
    Route::post('exit/{exit}/checklist', [\App\Modules\Exit\Controllers\ExitRequestController::class, 'updateChecklist'])->name('exit.checklist');
    Route::post('exit/{exit}/clearance', [\App\Modules\Exit\Controllers\ExitRequestController::class, 'completeClearance'])->name('exit.clearance');
    Route::post('exit/{exit}/settlement', [\App\Modules\Exit\Controllers\ExitRequestController::class, 'recordSettlement'])->name('exit.settlement');

    // Performance Management
    Route::prefix('performance')->name('performance.')->middleware('can:view performance')->group(function () {
        Route::resource('cycles', \App\Modules\Performance\Controllers\PerformanceReviewCycleController::class);
        Route::resource('goals', \App\Modules\Performance\Controllers\GoalController::class)->except(['show']);
        Route::get('reviews', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/create', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'create'])->name('reviews.create');
        Route::post('reviews', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'store'])->name('reviews.store');
        Route::get('reviews/{review}', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'show'])->name('reviews.show');
        Route::delete('reviews/{review}', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('reviews/{review}/self-review', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'selfReview'])->name('reviews.self-review');
        Route::post('reviews/{review}/self-review', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'submitSelfReview'])->name('reviews.self-review.submit');
        Route::get('reviews/{review}/manager-review', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'managerReview'])->name('reviews.manager-review');
        Route::post('reviews/{review}/manager-review', [\App\Modules\Performance\Controllers\PerformanceReviewController::class, 'submitManagerReview'])->name('reviews.manager-review.submit');
    });
});

require __DIR__ . '/auth.php';
