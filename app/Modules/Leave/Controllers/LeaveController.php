<?php

namespace App\Modules\Leave\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Leave\Models\Leave;
use App\Modules\Leave\Services\LeaveService;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Employee\Models\Employee;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function __construct(
        private LeaveService $leaveService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee) {
            return redirect()->route('ess.leaves');
        }

        $this->authorize('view leaves');

        return view('leave.index');
    }

    public function create()
    {
        $this->authorize('create leaves');

        return view('leave.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create leaves');

        $user = auth()->user();
        $employee = $user->employee;

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:2000',
        ]);

        if ($employee && (int) $validated['employee_id'] !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $this->leaveService->apply(
                (int) $validated['employee_id'],
                (int) $validated['leave_type_id'],
                $validated['start_date'],
                $validated['end_date'],
                $validated['reason'],
                $user->id
            );
        } catch (\DomainException|\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($employee) {
            return redirect()->route('ess.leaves')->with('success', 'Leave request created successfully.');
        }
        return redirect()->route('leaves.index')->with('success', 'Leave request created successfully.');
    }

    public function show(Leave $leave)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee && $leave->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }
        $this->authorize('view', $leave);

        $leave->load(['employee', 'leaveType']);
        return view('leave.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee && $leave->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }
        $this->authorize('update', $leave);

        $leaveTypes = LeaveType::where('is_active', true)->get();
        $employees = $employee ? collect([$employee]) : Employee::where('is_active', true)->get();

        return view('leave.edit', compact('leave', 'leaveTypes', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($employee && $leave->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }
        $this->authorize('update', $leave);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:2000',
        ]);

        if ($employee && (int) $validated['employee_id'] !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $totalDays = LeaveService::calculateTotalDays($validated['start_date'], $validated['end_date']);
        $leave->update([
            'employee_id' => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'updated_by' => $user->id,
        ]);

        if ($employee) {
            return redirect()->route('ess.leaves')->with('success', 'Leave request updated successfully.');
        }
        return redirect()->route('leaves.index')->with('success', 'Leave request updated successfully.');
    }

    public function destroy(Leave $leave)
    {
        $this->authorize('delete', $leave);
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave request deleted successfully.');
    }
}
