<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Modules\Employee\Models\Employee;
use App\Modules\Employee\Models\Department;
use App\Modules\Employee\Models\Designation;
use App\Modules\Employee\Models\Location;
use App\Modules\Employee\Models\EmploymentType;
use App\Modules\Employee\Models\EmploymentStatus;
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

        // Use filled() so empty query params don't filter everything out
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        if ($request->filled('status')) {
            $query->where('employment_status', $request->string('status')->toString());
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
        $employmentTypes = EmploymentType::active()->ordered()->get();
        $employmentStatuses = EmploymentStatus::active()->ordered()->get();

        return view('employee.create', compact('departments', 'designations', 'locations', 'managers', 'employees', 'employmentTypes', 'employmentStatuses'));
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
            'employment_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!EmploymentType::where('slug', $value)->where('is_active', true)->exists()) {
                    $fail('The selected employment type is invalid.');
                }
            }],
            'ctc' => 'nullable|numeric|min:0',
        ]);

        $validated['uuid'] = Str::uuid();
        $validated['created_by'] = auth()->id();
        $validated['employment_status'] = $validated['employment_status'] ?? 'active';
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

        return redirect()->route('employees.index')->with('success', __('messages.employee_created_success'));
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
        
        // Filter designations by employee's department
        // Show designations that match the employee's department OR have no department assigned
        // Always include the current designation (if set) so it doesn't disappear when editing
        $designations = Designation::where('is_active', true)
            ->where(function($q) use ($employee) {
                if ($employee->department_id) {
                    // Show designations matching employee's department OR with no department
                    $q->where('department_id', $employee->department_id)
                      ->orWhereNull('department_id');
                } else {
                    // If employee has no department, show only designations with no department
                    $q->whereNull('department_id');
                }
                // Always include current designation even if it doesn't match department
                if ($employee->designation_id) {
                    $q->orWhere('id', $employee->designation_id);
                }
            })
            ->get();
        
        $locations = Location::where('is_active', true)->get();
        $managers = Employee::where('is_active', true)->where('id', '!=', $employee->id)->get();
        $employees = Employee::where('is_active', true)->where('id', '!=', $employee->id)->get();
        $employmentTypes = EmploymentType::active()->ordered()->get();
        $employmentStatuses = EmploymentStatus::active()->ordered()->get();

        return view('employee.edit', compact('employee', 'departments', 'designations', 'locations', 'managers', 'employees', 'employmentTypes', 'employmentStatuses'));
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
            'employment_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!EmploymentType::where('slug', $value)->where('is_active', true)->exists()) {
                    $fail('The selected employment type is invalid.');
                }
            }],
            'employment_status' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!EmploymentStatus::where('slug', $value)->where('is_active', true)->exists()) {
                    $fail('The selected employment status is invalid.');
                }
            }],
            'ctc' => 'nullable|numeric|min:0',
        ]);

        $validated['updated_by'] = auth()->id();

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', __('messages.employee_updated_success'));
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete employees');

        $employee->delete();

        return redirect()->route('employees.index')->with('success', __('messages.employee_deleted_success'));
    }
}

