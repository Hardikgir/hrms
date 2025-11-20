<?php

namespace App\Modules\Attendance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Models\Attendance;
use App\Modules\Employee\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        // If user is an employee, redirect to ESS attendance
        if ($employee) {
            return redirect()->route('ess.attendance');
        }

        // Admin/HR can view all attendance
        $this->authorize('view attendance');

        $query = Attendance::with('employee')->latest('date');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $attendances = $query->paginate(25);
        $employees = Employee::orderBy('first_name')->get();

        return view('attendance.index', compact('attendances', 'employees'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        // If user is an employee, they can only check in for themselves
        if ($employee) {
            $validated = $request->validate([
                'method' => 'nullable|in:web,gps,biometric,manual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);

            // Check if already checked in today
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', today())
                ->first();

            if ($todayAttendance) {
                return redirect()->back()->with('error', 'You have already checked in today.');
            }

            Attendance::create([
                'uuid' => (string) Str::uuid(),
                'employee_id' => $employee->id,
                'date' => now()->toDateString(),
                'check_in_time' => now(),
                'check_in_method' => $validated['method'] ?? 'web',
                'check_in_latitude' => $validated['latitude'] ?? null,
                'check_in_longitude' => $validated['longitude'] ?? null,
                'status' => 'present',
                'created_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Check-in recorded successfully.');
        } else {
            // Admin/HR can check in any employee
            $this->authorize('create attendance');

            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'method' => 'nullable|in:web,gps,biometric,manual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);

            Attendance::create([
                'uuid' => (string) Str::uuid(),
                'employee_id' => $validated['employee_id'],
                'date' => now()->toDateString(),
                'check_in_time' => now(),
                'check_in_method' => $validated['method'] ?? 'web',
                'check_in_latitude' => $validated['latitude'] ?? null,
                'check_in_longitude' => $validated['longitude'] ?? null,
                'status' => 'present',
                'created_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Check-in recorded successfully.');
        }
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        // If user is an employee, they can only check out for themselves
        if ($employee) {
            $validated = $request->validate([
                'attendance_id' => 'required|exists:attendances,id',
                'method' => 'nullable|in:web,gps,biometric,manual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);

            $attendance = Attendance::findOrFail($validated['attendance_id']);

            // Ensure employee can only check out their own attendance
            if ($attendance->employee_id !== $employee->id) {
                abort(403, 'Unauthorized access.');
            }

            if ($attendance->check_out_time) {
                return redirect()->back()->with('error', 'You have already checked out today.');
            }

            $attendance->update([
                'check_out_time' => now(),
                'check_out_method' => $validated['method'] ?? 'web',
                'check_out_latitude' => $validated['latitude'] ?? null,
                'check_out_longitude' => $validated['longitude'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Check-out recorded successfully.');
        } else {
            // Admin/HR can check out any employee
            $this->authorize('update attendance');

            $validated = $request->validate([
                'attendance_id' => 'required|exists:attendances,id',
                'method' => 'nullable|in:web,gps,biometric,manual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ]);

            $attendance = Attendance::findOrFail($validated['attendance_id']);

            $attendance->update([
                'check_out_time' => now(),
                'check_out_method' => $validated['method'] ?? 'web',
                'check_out_latitude' => $validated['latitude'] ?? null,
                'check_out_longitude' => $validated['longitude'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Check-out recorded successfully.');
        }
    }
}

