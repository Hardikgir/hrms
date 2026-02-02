<?php

namespace App\Modules\Performance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Performance\Models\Goal;
use App\Modules\Performance\Models\PerformanceReviewCycle;
use App\Modules\Performance\Services\PerformanceService;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function __construct(
        private PerformanceService $performanceService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Goal::class);
        $employeeId = $request->query('employee_id');
        $cycleId = $request->query('cycle_id');
        $status = $request->query('status');
        $goals = $this->performanceService->listGoals($employeeId ? (int) $employeeId : null, $cycleId ? (int) $cycleId : null, $status);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $cycles = PerformanceReviewCycle::orderBy('period_start', 'desc')->get();
        return view('performance.goals.index', compact('goals', 'employees', 'cycles'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Goal::class);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $cycles = PerformanceReviewCycle::orderBy('period_start', 'desc')->get();
        return view('performance.goals.create', compact('employees', 'cycles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Goal::class);
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'cycle_id' => 'nullable|exists:performance_review_cycles,id',
            'type' => 'required|in:kra,okr',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'target_value' => 'nullable|string|max:100',
            'target_unit' => 'nullable|string|max:50',
            'weight' => 'nullable|integer|min:1|max:100',
            'status' => 'nullable|in:draft,active,achieved,not_achieved',
            'due_date' => 'nullable|date',
        ]);
        $this->performanceService->createGoal(
            (int) $validated['employee_id'],
            $validated['type'],
            $validated['title'],
            [
                'description' => $validated['description'] ?? null,
                'target_value' => $validated['target_value'] ?? null,
                'target_unit' => $validated['target_unit'] ?? null,
                'weight' => (int) ($validated['weight'] ?? 100),
                'status' => $validated['status'] ?? Goal::STATUS_ACTIVE,
                'due_date' => $validated['due_date'] ?? null,
                'cycle_id' => $validated['cycle_id'] ?? null,
            ],
            auth()->id()
        );
        return redirect()->route('performance.goals.index')->with('success', 'Goal created.');
    }

    public function edit(Goal $goal)
    {
        $this->authorize('update', $goal);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $cycles = PerformanceReviewCycle::orderBy('period_start', 'desc')->get();
        return view('performance.goals.edit', compact('goal', 'employees', 'cycles'));
    }

    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'cycle_id' => 'nullable|exists:performance_review_cycles,id',
            'type' => 'required|in:kra,okr',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'target_value' => 'nullable|string|max:100',
            'target_unit' => 'nullable|string|max:50',
            'weight' => 'nullable|integer|min:1|max:100',
            'status' => 'nullable|in:draft,active,achieved,not_achieved',
            'due_date' => 'nullable|date',
            'achieved_value' => 'nullable|string|max:100',
        ]);
        $this->performanceService->updateGoal($goal, $validated);
        return redirect()->route('performance.goals.index')->with('success', 'Goal updated.');
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        $goal->delete();
        return redirect()->route('performance.goals.index')->with('success', 'Goal deleted.');
    }
}
