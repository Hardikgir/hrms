<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Employee\Resources\EmployeeResource;
use Illuminate\Http\Request;

class EmployeeApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
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

        $employees = $query->latest()->paginate($request->per_page ?? 20);

        return EmployeeResource::collection($employees);
    }

    public function show(Employee $employee)
    {
        $this->authorize('view employees');

        $employee->load(['department', 'designation', 'location', 'manager', 'user']);

        return new EmployeeResource($employee);
    }

    public function store(Request $request)
    {
        $this->authorize('create employees');

        $validated = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'joining_date' => 'required|date',
        ]);

        $employee = Employee::create($validated);

        return new EmployeeResource($employee);
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update employees');

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:employees,email,' . $employee->id,
            'department_id' => 'sometimes|required|exists:departments,id',
            'designation_id' => 'sometimes|required|exists:designations,id',
        ]);

        $employee->update($validated);

        return new EmployeeResource($employee);
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete employees');

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully.']);
    }
}

