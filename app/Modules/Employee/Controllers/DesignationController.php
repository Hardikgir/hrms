<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Designation;
use App\Modules\Employee\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage designations');
        $designations = Designation::with('department')->withCount('employees')->orderBy('name')->paginate(20);
        return view('employee.designations.index', compact('designations'));
    }

    public function create()
    {
        $this->authorize('manage designations');
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('employee.designations.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage designations');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:designations,code',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['uuid'] = (string) Str::uuid();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['created_by'] = auth()->id();
        Designation::create($validated);
        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }

    public function edit(Designation $designation)
    {
        $this->authorize('manage designations');
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('employee.designations.edit', compact('designation', 'departments'));
    }

    public function update(Request $request, Designation $designation)
    {
        $this->authorize('manage designations');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:designations,code,' . $designation->id,
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['updated_by'] = auth()->id();
        $designation->update($validated);
        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $this->authorize('manage designations');
        if ($designation->employees()->exists()) {
            return redirect()->route('designations.index')->with('error', 'Cannot delete: employees are assigned to this designation.');
        }
        $designation->delete();
        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}
