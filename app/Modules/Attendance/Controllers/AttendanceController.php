<?php

namespace App\Modules\Attendance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        private AttendanceService $attendanceService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee) {
            return redirect()->route('ess.attendance');
        }

        $this->authorize('view attendance');

        return view('attendance.index');
    }

    public function checkIn(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee) {
            $validated = $request->validate([
                'method' => 'nullable|in:web,gps,biometric,manual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);
            try {
                $this->attendanceService->checkIn(
                    $employee->id,
                    $validated['method'] ?? 'web',
                    $validated['latitude'] ?? null,
                    $validated['longitude'] ?? null,
                    $user->id
                );
                return redirect()->back()->with('success', 'Check-in recorded successfully.');
            } catch (\DomainException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        $this->authorize('create attendance');
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'method' => 'nullable|in:web,gps,biometric,manual',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
        $this->attendanceService->checkIn(
            (int) $validated['employee_id'],
            $validated['method'] ?? 'web',
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null,
            $user->id
        );
        return redirect()->back()->with('success', 'Check-in recorded successfully.');
    }

    public function checkOut(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee) {
            $validated = $request->validate([
                'attendance_id' => 'required|exists:attendances,id',
                'method' => 'nullable|in:web,gps,biometric,manual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);
            try {
                $this->attendanceService->checkOut(
                    (int) $validated['attendance_id'],
                    $employee->id,
                    $validated['method'] ?? 'web',
                    $validated['latitude'] ?? null,
                    $validated['longitude'] ?? null,
                    $user->id
                );
                return redirect()->back()->with('success', 'Check-out recorded successfully.');
            } catch (\DomainException|\Illuminate\Auth\Access\AuthorizationException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        $this->authorize('update attendance');
        $validated = $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'method' => 'nullable|in:web,gps,biometric,manual',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
        $attendance = \App\Modules\Attendance\Models\Attendance::findOrFail($validated['attendance_id']);
        $this->attendanceService->checkOut(
            (int) $validated['attendance_id'],
            $attendance->employee_id,
            $validated['method'] ?? 'web',
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null,
            $user->id
        );
        return redirect()->back()->with('success', 'Check-out recorded successfully.');
    }
}
