<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\EmployeeTask;
use App\Modules\Employee\Models\Employee;
use App\Modules\Leave\Models\Leave;
use Illuminate\Http\Request;

class EmployeeTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('manage tasks');

        $query = EmployeeTask::with(['employee', 'createdBy', 'approvedBy'])->latest('due_date');

        if ($request->filled('employee_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('employee_id', $request->employee_id)->orWhereNull('employee_id');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->paginate(20);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();

        return view('employee.tasks.index', compact('tasks', 'employees'));
    }

    public function create()
    {
        $this->authorize('manage tasks');

        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();

        return view('employee.tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage tasks');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'employee_id' => 'nullable|exists:employees,id',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,approved',
            'action_route' => 'nullable|string|max:100',
            'action_label' => 'nullable|string|max:50',
        ]);

        // Check if employee has approved leave on due_date
        if ($validated['employee_id'] && Leave::hasApprovedLeaveOnDate($validated['employee_id'], $validated['due_date'])) {
            return back()
                ->withInput()
                ->withErrors(['due_date' => 'Cannot assign task to employee on a day when they have an approved leave.']);
        }

        $validated['created_by'] = auth()->id();

        EmployeeTask::create($validated);

        return redirect()->route('employee-tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(EmployeeTask $employee_task)
    {
        $this->authorize('manage tasks');

        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();

        return view('employee.tasks.edit', compact('employee_task', 'employees'));
    }

    public function update(Request $request, EmployeeTask $employee_task)
    {
        $this->authorize('manage tasks');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'employee_id' => 'nullable|exists:employees,id',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,approved',
            'action_route' => 'nullable|string|max:100',
            'action_label' => 'nullable|string|max:50',
        ]);

        // Check if employee has approved leave on due_date (only if employee_id or due_date changed)
        if ($validated['employee_id'] && 
            ($employee_task->employee_id != $validated['employee_id'] || 
             $employee_task->due_date->format('Y-m-d') != $validated['due_date'])) {
            
            if (Leave::hasApprovedLeaveOnDate($validated['employee_id'], $validated['due_date'])) {
                return back()
                    ->withInput()
                    ->withErrors(['due_date' => 'Cannot assign task to employee on a day when they have an approved leave.']);
            }
        }

        $employee_task->update($validated);

        return redirect()->route('employee-tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(EmployeeTask $employee_task)
    {
        $this->authorize('manage tasks');

        $employee_task->delete();

        return redirect()->route('employee-tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function approve(EmployeeTask $employee_task)
    {
        $this->authorize('manage tasks');

        if ($employee_task->status !== 'completed') {
            return redirect()->route('employee-tasks.index')->with('error', 'Only completed tasks can be approved.');
        }

        $employee_task->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('employee-tasks.index')->with('success', 'Task approved. It will no longer appear in the employee\'s task list.');
    }
}
