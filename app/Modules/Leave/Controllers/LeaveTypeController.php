<?php

namespace App\Modules\Leave\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Leave\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeaveTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('view leave types');
        
        $leaveTypes = LeaveType::orderBy('name')->paginate(15);
        return view('leave_types.index', compact('leaveTypes'));
    }

    public function create()
    {
        $this->authorize('manage leave types');
        
        return view('leave_types.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage leave types');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:leave_types,code',
            'description' => 'nullable|string|max:1000',
            'max_days_per_year' => 'nullable|integer|min:0|max:365',
            'is_paid' => 'boolean',
            'requires_approval' => 'boolean',
            'can_carry_forward' => 'boolean',
            'carry_forward_limit' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['uuid'] = (string) Str::uuid();
        $validated['created_by'] = auth()->id();
        $validated['is_paid'] = $request->has('is_paid');
        $validated['requires_approval'] = $request->has('requires_approval');
        $validated['can_carry_forward'] = $request->has('can_carry_forward');
        $validated['is_active'] = $request->has('is_active');

        LeaveType::create($validated);

        return redirect()->route('leave-types.index')
            ->with('success', __('messages.leave_type_created_success'));
    }

    public function edit(LeaveType $leaveType)
    {
        $this->authorize('manage leave types');
        
        return view('leave_types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $this->authorize('manage leave types');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:leave_types,code,' . $leaveType->id,
            'description' => 'nullable|string|max:1000',
            'max_days_per_year' => 'nullable|integer|min:0|max:365',
            'is_paid' => 'boolean',
            'requires_approval' => 'boolean',
            'can_carry_forward' => 'boolean',
            'carry_forward_limit' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id();
        $validated['is_paid'] = $request->has('is_paid');
        $validated['requires_approval'] = $request->has('requires_approval');
        $validated['can_carry_forward'] = $request->has('can_carry_forward');
        $validated['is_active'] = $request->has('is_active');

        $leaveType->update($validated);

        return redirect()->route('leave-types.index')
            ->with('success', __('messages.leave_type_updated_success'));
    }

    public function destroy(LeaveType $leaveType)
    {
        $this->authorize('manage leave types');
        
        // Prevent deletion if associated leaves exist
        if (\App\Modules\Leave\Models\Leave::where('leave_type_id', $leaveType->id)->exists()) {
            return redirect()->route('leave-types.index')
                ->with('error', __('messages.cannot_delete_leave_type_in_use'));
        }

        $leaveType->delete();

        return redirect()->route('leave-types.index')
            ->with('success', __('messages.leave_type_deleted_success'));
    }
}
