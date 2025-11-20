<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Employee\Controllers\EmployeeApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    // Employee API Routes
    Route::apiResource('employees', EmployeeApiController::class)->names([
        'index' => 'api.employees.index',
        'store' => 'api.employees.store',
        'show' => 'api.employees.show',
        'update' => 'api.employees.update',
        'destroy' => 'api.employees.destroy',
    ]);
    
    // Attendance API Routes
    Route::prefix('attendance')->name('api.attendance.')->group(function () {
        Route::post('/check-in', [\App\Modules\Attendance\Controllers\AttendanceApiController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [\App\Modules\Attendance\Controllers\AttendanceApiController::class, 'checkOut'])->name('check-out');
    });
    
    // Leave API Routes
    Route::apiResource('leaves', \App\Modules\Leave\Controllers\LeaveApiController::class)->names([
        'index' => 'api.leaves.index',
        'store' => 'api.leaves.store',
        'show' => 'api.leaves.show',
        'update' => 'api.leaves.update',
        'destroy' => 'api.leaves.destroy',
    ]);
    
    // Payroll API Routes
    Route::prefix('payroll')->name('api.payroll.')->group(function () {
        Route::get('/', [\App\Modules\Payroll\Controllers\PayrollApiController::class, 'index'])->name('index');
        Route::post('/run', [\App\Modules\Payroll\Controllers\PayrollApiController::class, 'run'])->name('run');
    });
});
