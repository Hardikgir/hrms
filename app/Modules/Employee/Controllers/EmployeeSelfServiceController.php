<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Attendance\Models\Attendance;
use App\Modules\Leave\Models\Leave;
use App\Modules\Payroll\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmployeeSelfServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Employee Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }

        // Get today's attendance
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        // Get pending leaves
        $pendingLeaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->count();

        // Get recent leaves
        $recentLeaves = Leave::where('employee_id', $employee->id)
            ->latest()
            ->limit(5)
            ->get();

        // Get recent attendance
        $recentAttendance = Attendance::where('employee_id', $employee->id)
            ->latest('date')
            ->limit(10)
            ->get();

        // Get latest payslip
        $latestPayslip = Payroll::where('employee_id', $employee->id)
            ->latest()
            ->first();

        return view('employee.ess.dashboard', compact(
            'employee',
            'todayAttendance',
            'pendingLeaves',
            'recentLeaves',
            'recentAttendance',
            'latestPayslip'
        ));
    }

    /**
     * Employee Profile View
     */
    public function profile()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', 'Employee record not found.');
        }

        $employee->load(['department', 'designation', 'location', 'manager']);

        return view('employee.ess.profile', compact('employee'));
    }

    /**
     * Employee Profile Edit
     */
    public function editProfile()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', 'Employee record not found.');
        }

        return view('employee.ess.edit-profile', compact('employee'));
    }

    /**
     * Update Employee Profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', 'Employee record not found.');
        }

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:255',
        ]);

        $employee->update($validated);

        return redirect()->route('ess.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Employee Tasks
     */
    public function tasks()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', 'Employee record not found.');
        }

        // For now, we'll create a simple task system
        // In a full implementation, you'd have a tasks table
        $tasks = [
            [
                'id' => 1,
                'title' => 'Complete onboarding documents',
                'description' => 'Submit all required documents for onboarding',
                'status' => 'pending',
                'due_date' => now()->addDays(7),
                'priority' => 'high',
            ],
            [
                'id' => 2,
                'title' => 'Attend training session',
                'description' => 'Complete mandatory training session',
                'status' => 'in_progress',
                'due_date' => now()->addDays(3),
                'priority' => 'medium',
            ],
        ];

        return view('employee.ess.tasks', compact('employee', 'tasks'));
    }

    /**
     * Employee Attendance View
     */
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', 'Employee record not found.');
        }

        $query = Attendance::where('employee_id', $employee->id)->latest('date');

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $attendances = $query->paginate(30);

        // Get today's attendance
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        return view('employee.ess.attendance', compact('employee', 'attendances', 'todayAttendance'));
    }

    /**
     * Employee Leaves View
     */
    public function leaves(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', 'Employee record not found.');
        }

        $query = Leave::where('employee_id', $employee->id)->with('leaveType')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->paginate(20);
        $leaveTypes = \App\Modules\Leave\Models\LeaveType::where('is_active', true)->get();

        return view('employee.ess.leaves', compact('employee', 'leaves', 'leaveTypes'));
    }

    /**
     * Employee Payslips View
     */
    public function payslips(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('ess.dashboard')->with('error', 'Employee record not found.');
        }

        $query = Payroll::where('employee_id', $employee->id)->latest();

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        $payrolls = $query->paginate(12);

        return view('employee.ess.payslips', compact('employee', 'payrolls'));
    }

    /**
     * View Single Payslip
     */
    public function viewPayslip(Payroll $payroll)
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Ensure employee can only view their own payslip
        if (!$employee || $payroll->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $payroll->load(['employee.department', 'employee.designation']);

        return view('payroll.show', compact('payroll'));
    }
}

