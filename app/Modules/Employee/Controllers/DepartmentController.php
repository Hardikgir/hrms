<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Department;
use App\Modules\Employee\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage departments');
        $departments = Department::with('parent', 'managers')->withCount('employees')->orderBy('name')->paginate(20);
        return view('employee.departments.index', compact('departments'));
    }

    public function create()
    {
        $this->authorize('manage departments');
        $parentDepartments = Department::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('first_name')->orderBy('last_name')->get();
        return view('employee.departments.create', compact('parentDepartments', 'employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage departments');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_ids' => 'nullable|array',
            'manager_ids.*' => 'exists:employees,id',
            'is_active' => 'boolean',
        ]);
        $validated['uuid'] = (string) Str::uuid();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['created_by'] = auth()->id();
        
        $managerIds = $validated['manager_ids'] ?? [];
        unset($validated['manager_ids']);
        
        $department = Department::create($validated);
        
        // Sync managers
        if (!empty($managerIds)) {
            $department->managers()->sync($managerIds);
        }
        
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $this->authorize('manage departments');
        $parentDepartments = Department::where('is_active', true)->where('id', '!=', $department->id)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('first_name')->orderBy('last_name')->get();
        $selectedManagers = $department->managers->pluck('id')->toArray();
        return view('employee.departments.edit', compact('department', 'parentDepartments', 'employees', 'selectedManagers'));
    }

    public function update(Request $request, Department $department)
    {
        $this->authorize('manage departments');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_ids' => 'nullable|array',
            'manager_ids.*' => 'exists:employees,id',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['updated_by'] = auth()->id();
        
        $managerIds = $validated['manager_ids'] ?? [];
        unset($validated['manager_ids']);
        
        $department->update($validated);
        
        // Sync managers
        $department->managers()->sync($managerIds);
        
        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $this->authorize('manage departments');
        if ($department->employees()->exists()) {
            return redirect()->route('departments.index')->with('error', 'Cannot delete: employees are assigned to this department.');
        }
        if ($department->children()->exists()) {
            return redirect()->route('departments.index')->with('error', 'Cannot delete: has sub-departments. Remove or reassign them first.');
        }
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
