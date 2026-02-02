<?php

namespace App\Modules\Shift\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Shift\Models\Shift;
use App\Modules\Shift\Services\ShiftService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShiftController extends Controller
{
    public function __construct(private ShiftService $shiftService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Shift::class);
        $activeOnly = $request->query('active', true);
        $shifts = $this->shiftService->listShifts(filter_var($activeOnly, FILTER_VALIDATE_BOOLEAN));
        return view('shift.index', compact('shifts'));
    }

    public function create()
    {
        $this->authorize('create', Shift::class);
        return view('shift.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Shift::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration' => 'nullable|integer|min:0',
            'working_hours' => 'nullable|integer|min:0|max:24',
            'is_flexible' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['break_duration'] = $validated['break_duration'] ?? 0;
        $validated['working_hours'] = $validated['working_hours'] ?? 8;
        $validated['is_flexible'] = $request->boolean('is_flexible', false);
        $validated['is_active'] = $request->boolean('is_active', true);
        $this->shiftService->createShift($validated, auth()->id());
        return redirect()->route('shifts.index')->with('success', 'Shift created.');
    }

    public function edit(Shift $shift)
    {
        $this->authorize('update', $shift);
        return view('shift.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $this->authorize('update', $shift);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration' => 'nullable|integer|min:0',
            'working_hours' => 'nullable|integer|min:0|max:24',
            'is_flexible' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_flexible'] = $request->boolean('is_flexible', false);
        $validated['is_active'] = $request->boolean('is_active', true);
        $this->shiftService->updateShift($shift, $validated);
        return redirect()->route('shifts.index')->with('success', 'Shift updated.');
    }

    public function destroy(Shift $shift)
    {
        $this->authorize('delete', $shift);
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift deleted.');
    }
}
