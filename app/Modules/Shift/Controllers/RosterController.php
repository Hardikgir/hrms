<?php

namespace App\Modules\Shift\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Shift\Models\Shift;
use App\Modules\Shift\Models\ShiftAssignment;
use App\Modules\Shift\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    public function __construct(private ShiftService $shiftService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', ShiftAssignment::class);
        $weekStart = $request->query('week') ? Carbon::parse($request->query('week'))->startOfWeek() : now()->startOfWeek();
        $dateFrom = $weekStart->copy();
        $dateTo = $weekStart->copy()->endOfWeek();
        $roster = $this->shiftService->getRoster($dateFrom, $dateTo);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $shifts = Shift::where('is_active', true)->orderBy('name')->get();
        return view('shift.roster.index', compact('roster', 'dateFrom', 'dateTo', 'employees', 'shifts'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', ShiftAssignment::class);
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'assignment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);
        try {
            $this->shiftService->assignShift(
                (int) $validated['employee_id'],
                (int) $validated['shift_id'],
                $validated['assignment_date'],
                $validated['notes'] ?? null,
                auth()->id()
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', __('messages.shift_assigned_success'));
    }

    public function destroy(ShiftAssignment $roster)
    {
        $this->authorize('delete', $roster);
        $this->shiftService->removeAssignment($roster);
        return back()->with('success', __('messages.assignment_removed_success'));
    }
}
