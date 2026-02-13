<?php

namespace App\Modules\Performance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Performance\Models\PerformanceReviewCycle;
use App\Modules\Performance\Services\PerformanceService;
use Illuminate\Http\Request;

class PerformanceReviewCycleController extends Controller
{
    public function __construct(
        private PerformanceService $performanceService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', PerformanceReviewCycle::class);
        $cycles = $this->performanceService->listCycles($request->query('status'));
        return view('performance.cycles.index', compact('cycles'));
    }

    public function create()
    {
        $this->authorize('create', PerformanceReviewCycle::class);
        return view('performance.cycles.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', PerformanceReviewCycle::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);
        $this->performanceService->createCycle(
            $validated['name'],
            $validated['period_start'],
            $validated['period_end'],
            auth()->id()
        );
        return redirect()->route('performance.cycles.index')->with('success', __('messages.cycle_created_success'));
    }

    public function show(PerformanceReviewCycle $cycle)
    {
        $this->authorize('view', $cycle);
        $cycle->load(['reviews.employee', 'reviews.reviewer']);
        return view('performance.cycles.show', compact('cycle'));
    }

    public function edit(PerformanceReviewCycle $cycle)
    {
        $this->authorize('update', $cycle);
        return view('performance.cycles.edit', compact('cycle'));
    }

    public function update(Request $request, PerformanceReviewCycle $cycle)
    {
        $this->authorize('update', $cycle);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'status' => 'nullable|in:draft,active,closed',
        ]);
        $this->performanceService->updateCycle($cycle, $validated, auth()->id());
        return redirect()->route('performance.cycles.show', $cycle)->with('success', __('messages.cycle_updated_success'));
    }

    public function destroy(PerformanceReviewCycle $cycle)
    {
        $this->authorize('delete', $cycle);
        $cycle->delete();
        return redirect()->route('performance.cycles.index')->with('success', __('messages.cycle_deleted_success'));
    }
}
