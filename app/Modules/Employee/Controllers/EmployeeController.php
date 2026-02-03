<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Modules\Employee\Models\Employee;
use App\Modules\Employee\Models\Department;
use App\Modules\Employee\Models\Designation;
use App\Modules\Employee\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('view employees');

        $query = Employee::with(['department', 'designation', 'location', 'manager']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('status')) {
            $query->where('employment_status', $request->status);
        }

        $employees = $query->latest()->paginate(20);
        $departments = Department::where('is_active', true)->get();
        $designations = Designation::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();

        return view('employee.index', compact('employees', 'departments', 'designations', 'locations'));
    }

    public function create()
    {
        $this->authorize('create employees');

        $departments = Department::where('is_active', true)->get();
        $designations = Designation::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        $managers = Employee::where('is_active', true)->get();
        $employees = Employee::where('is_active', true)->get();

        return view('employee.create', compact('departments', 'designations', 'locations', 'managers', 'employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create employees');

        $validated = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'location_id' => 'nullable|exists:locations,id',
            'manager_id' => 'nullable|exists:employees,id',
            'joining_date' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract,intern,temporary',
            'ctc' => 'nullable|numeric|min:0',
        ]);

        $validated['uuid'] = Str::uuid();
        $validated['created_by'] = auth()->id();
        $validated['employment_status'] = 'active';
        $validated['is_active'] = true;

        $employee = Employee::create($validated);

        // Auto-create user account
        if ($request->has('create_user_account') && $request->create_user_account) {
            $user = \App\Models\User::create([
                'name' => "{$employee->first_name} {$employee->last_name}",
                'email' => $employee->email,
                'password' => bcrypt($request->password ?? 'password123'),
                'employee_id' => $employee->id,
                'is_active' => true,
            ]);
            $user->assignRole('Employee');
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $this->authorize('view employees');

        $employee->load(['department', 'designation', 'location', 'manager', 'user', 'documents']);

        return view('employee.show', compact('employee'));
    }

    public function downloadDocument(Employee $employee, Document $document)
    {
        $this->authorize('view employees');
        if ($document->employee_id !== $employee->id) {
            abort(404);
        }
        if (!Storage::disk($document->disk)->exists($document->path)) {
            abort(404, 'File not found.');
        }
        return Storage::disk($document->disk)->download(
            $document->path,
            $document->original_name ?? 'document'
        );
    }

    public function edit(Employee $employee)
    {
        $this->authorize('update employees');

        $departments = Department::where('is_active', true)->get();
        $designations = Designation::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        $managers = Employee::where('is_active', true)->where('id', '!=', $employee->id)->get();
        $employees = Employee::where('is_active', true)->where('id', '!=', $employee->id)->get();

        return view('employee.edit', compact('employee', 'departments', 'designations', 'locations', 'managers', 'employees'));
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update employees');

        $validated = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'location_id' => 'nullable|exists:locations,id',
            'manager_id' => 'nullable|exists:employees,id',
            'joining_date' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract,intern,temporary',
            'employment_status' => 'required|in:active,inactive,terminated,resigned,on_leave',
            'ctc' => 'nullable|numeric|min:0',
        ]);

        $validated['updated_by'] = auth()->id();

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete employees');

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}

