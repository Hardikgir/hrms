<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Designation;
use App\Modules\Employee\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage designations');
        $designations = Designation::with('department')->withCount(['employees', 'permissions'])->orderBy('name')->paginate(20);
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
            'sidebar_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);
        $validated['uuid'] = (string) Str::uuid();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['created_by'] = auth()->id();
        Designation::create($validated);
        return redirect()->route('designations.index')->with('success', __('messages.designation_created_success'));
    }

    public function edit(Designation $designation)
    {
        $this->authorize('manage designations');
        $designation->load('permissions');
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get()->groupBy(function (Permission $permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0];
        });
        $designationPermissionIds = $designation->permissions->pluck('id')->toArray();
        return view('employee.designations.edit', compact('designation', 'departments', 'permissions', 'designationPermissionIds'));
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
            'sidebar_color' => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['updated_by'] = auth()->id();
        $designation->update(collect($validated)->except('permissions')->all());
        if (array_key_exists('permissions', $validated)) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $designation->syncPermissions($permissions);
        } else {
            $designation->syncPermissions([]);
        }
        return redirect()->route('designations.index')->with('success', __('messages.designation_updated_success'));
    }

    public function destroy(Designation $designation)
    {
        $this->authorize('manage designations');
        if ($designation->employees()->exists()) {
            return redirect()->route('designations.index')->with('error', __('messages.designation_cannot_delete_employees'));
        }
        $designation->delete();
        return redirect()->route('designations.index')->with('success', __('messages.designation_deleted_success'));
    }
}
