<?php

namespace App\Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\EmploymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmploymentStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('manage employment statuses');
        $employmentStatuses = EmploymentStatus::ordered()->paginate(20);
        return view('employee.employment-statuses.index', compact('employmentStatuses'));
    }

    public function create()
    {
        $this->authorize('manage employment statuses');
        return view('employee.employment-statuses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage employment statuses');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|alpha_dash|unique:employment_statuses,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['created_by'] = auth()->id();
        EmploymentStatus::create($validated);
        return redirect()->route('employment-statuses.index')->with('success', __('messages.employment_status_created'));
    }

    public function edit(EmploymentStatus $employment_status)
    {
        $this->authorize('manage employment statuses');
        return view('employee.employment-statuses.edit', compact('employment_status'));
    }

    public function update(Request $request, EmploymentStatus $employment_status)
    {
        $this->authorize('manage employment statuses');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|alpha_dash|unique:employment_statuses,slug,' . $employment_status->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['updated_by'] = auth()->id();
        $employment_status->update($validated);
        return redirect()->route('employment-statuses.index')->with('success', __('messages.employment_status_updated'));
    }

    public function destroy(EmploymentStatus $employment_status)
    {
        $this->authorize('manage employment statuses');
        if ($employment_status->employees()->exists()) {
            return redirect()->route('employment-statuses.index')->with('error', __('messages.employment_status_cannot_delete'));
        }
        $employment_status->delete();
        return redirect()->route('employment-statuses.index')->with('success', __('messages.employment_status_deleted'));
    }
}
