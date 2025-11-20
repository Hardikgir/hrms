<?php

namespace App\Modules\Leave\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Leave\Models\Leave;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Employee\Models\Employee;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        // If user is an employee, show only their leaves
        if ($employee) {
            return redirect()->route('ess.leaves');
        }

        // Admin/HR can view all leaves
        $this->authorize('view leaves');

        $query = Leave::with(['employee', 'leaveType']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->latest()->paginate(20);
        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('leave.index', compact('leaves', 'leaveTypes'));
    }

    public function create()
    {
        $user = auth()->user();
        $employee = $user->employee;

        $this->authorize('create leaves');

        $leaveTypes = LeaveType::where('is_active', true)->get();
        
        // If employee, only show their own data, otherwise show all employees
        if ($employee) {
            $employees = collect([$employee]);
        } else {
            $employees = Employee::where('is_active', true)->get();
        }

        return view('leave.create', compact('leaveTypes', 'employees'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        $this->authorize('create leaves');

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        // If employee, ensure they can only create leaves for themselves
        if ($employee && $validated['employee_id'] != $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['total_days'] = $startDate->diffInDays($endDate) + 1;
        $validated['uuid'] = \Illuminate\Support\Str::uuid();
        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();

        Leave::create($validated);

        // Redirect based on user role
        if ($employee) {
            return redirect()->route('ess.leaves')->with('success', 'Leave request created successfully.');
        }

        return redirect()->route('leaves.index')->with('success', 'Leave request created successfully.');
    }

    public function show(Leave $leave)
    {
        $user = auth()->user();
        $employee = $user->employee;

        // If employee, ensure they can only view their own leaves
        if ($employee && $leave->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $this->authorize('view leaves');

        $leave->load(['employee', 'leaveType']);

        return view('leave.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        $user = auth()->user();
        $employee = $user->employee;

        // If employee, ensure they can only edit their own leaves
        if ($employee && $leave->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $this->authorize('update leaves');

        $leaveTypes = LeaveType::where('is_active', true)->get();
        
        // If employee, only show their own data
        if ($employee) {
            $employees = collect([$employee]);
        } else {
            $employees = Employee::where('is_active', true)->get();
        }

        return view('leave.edit', compact('leave', 'leaveTypes', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $user = auth()->user();
        $employee = $user->employee;

        // If employee, ensure they can only update their own leaves
        if ($employee && $leave->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $this->authorize('update leaves');

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        // If employee, ensure they can only update leaves for themselves
        if ($employee && $validated['employee_id'] != $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['total_days'] = $startDate->diffInDays($endDate) + 1;
        $validated['updated_by'] = auth()->id();

        $leave->update($validated);

        // Redirect based on user role
        if ($employee) {
            return redirect()->route('ess.leaves')->with('success', 'Leave request updated successfully.');
        }

        return redirect()->route('leaves.index')->with('success', 'Leave request updated successfully.');
    }

    public function destroy(Leave $leave)
    {
        $this->authorize('delete leaves');

        $leave->delete();

        return redirect()->route('leaves.index')->with('success', 'Leave request deleted successfully.');
    }
}

