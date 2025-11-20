<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Employee\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        // Redirect employees to ESS dashboard
        if ($user->hasRole('Employee') && $user->employee) {
            return redirect()->route('ess.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Employee Self Service (ESS) Routes
    Route::prefix('ess')->name('ess.')->group(function () {
        Route::get('/dashboard', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'updateProfile'])->name('profile.update');
        Route::get('/tasks', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'tasks'])->name('tasks');
        Route::get('/attendance', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'attendance'])->name('attendance');
        Route::get('/leaves', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'leaves'])->name('leaves');
        Route::get('/payslips', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'payslips'])->name('payslips');
        Route::get('/payslips/{payroll}', [\App\Modules\Employee\Controllers\EmployeeSelfServiceController::class, 'viewPayslip'])->name('payslips.show');
    });

    // Admin/HR Routes (only for non-employees or with permissions)
    Route::middleware(['can:view employees'])->group(function () {
        // Employee Routes
        Route::resource('employees', EmployeeController::class);
    });
    
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
        Route::get('/{payroll}', [\App\Modules\Payroll\Controllers\PayrollController::class, 'show'])->name('show');
        Route::get('/run', [\App\Modules\Payroll\Controllers\PayrollController::class, 'run'])->name('run');
    });
});

require __DIR__.'/auth.php';
