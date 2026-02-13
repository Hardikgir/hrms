<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\EmploymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmploymentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage employment types');
        $employmentTypes = EmploymentType::ordered()->paginate(20);
        return view('employee.employment-types.index', compact('employmentTypes'));
    }

    public function create()
    {
        $this->authorize('manage employment types');
        return view('employee.employment-types.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage employment types');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|alpha_dash|unique:employment_types,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['created_by'] = auth()->id();
        EmploymentType::create($validated);
        return redirect()->route('employment-types.index')->with('success', __('messages.employment_type_created'));
    }

    public function edit(EmploymentType $employment_type)
    {
        $this->authorize('manage employment types');
        return view('employee.employment-types.edit', compact('employment_type'));
    }

    public function update(Request $request, EmploymentType $employment_type)
    {
        $this->authorize('manage employment types');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|alpha_dash|unique:employment_types,slug,' . $employment_type->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['updated_by'] = auth()->id();
        $employment_type->update($validated);
        return redirect()->route('employment-types.index')->with('success', __('messages.employment_type_updated'));
    }

    public function destroy(EmploymentType $employment_type)
    {
        $this->authorize('manage employment types');
        if ($employment_type->employees()->exists()) {
            return redirect()->route('employment-types.index')->with('error', __('messages.employment_type_cannot_delete'));
        }
        $employment_type->delete();
        return redirect()->route('employment-types.index')->with('success', __('messages.employment_type_deleted'));
    }
}
