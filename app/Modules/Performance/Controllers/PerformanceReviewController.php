<?php

namespace App\Modules\Performance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Employee\Models\Employee;
use App\Modules\Performance\Models\PerformanceReview;
use App\Modules\Performance\Models\PerformanceReviewCycle;
use App\Modules\Performance\Services\PerformanceService;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
    public function __construct(
        private PerformanceService $performanceService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', PerformanceReview::class);
        $employeeId = $request->query('employee_id') ? (int) $request->query('employee_id') : null;
        $cycleId = $request->query('cycle_id') ? (int) $request->query('cycle_id') : null;
        $status = $request->query('status');
        $reviews = $this->performanceService->listReviews($employeeId, $cycleId, $status);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $cycles = PerformanceReviewCycle::orderBy('period_start', 'desc')->get();
        return view('performance.reviews.index', compact('reviews', 'employees', 'cycles'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', PerformanceReview::class);
        $employees = Employee::where('is_active', true)->with('manager.user')->orderBy('first_name')->get();
        $cycles = PerformanceReviewCycle::whereIn('status', ['draft', 'active'])->orderBy('period_start', 'desc')->get();
        $reviewers = \App\Models\User::orderBy('name')->get();
        return view('performance.reviews.create', compact('employees', 'cycles', 'reviewers'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', PerformanceReview::class);
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'cycle_id' => 'required|exists:performance_review_cycles,id',
            'reviewer_id' => 'nullable|exists:users,id',
        ]);
        try {
            $this->performanceService->createReview(
                (int) $validated['employee_id'],
                (int) $validated['cycle_id'],
                $validated['reviewer_id'] ? (int) $validated['reviewer_id'] : null,
                auth()->id()
            );
        } catch (\DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        return redirect()->route('performance.reviews.index')->with('success', 'Review created.');
    }

    public function show(PerformanceReview $review)
    {
        $this->authorize('view', $review);
        $review = $this->performanceService->getReviewWithGoals($review->id);
        return view('performance.reviews.show', compact('review'));
    }

    public function selfReview(PerformanceReview $review)
    {
        $this->authorize('submitSelfReview', $review);
        $review = $this->performanceService->getReviewWithGoals($review->id);
        $this->performanceService->ensureGoalRatingsForReview($review);
        $review->load('goalRatings.goal');
        return view('performance.reviews.self-review', compact('review'));
    }

    public function submitSelfReview(Request $request, PerformanceReview $review)
    {
        $this->authorize('submitSelfReview', $review);
        $validated = $request->validate([
            'self_rating' => 'nullable|integer|min:1|max:5',
            'self_comments' => 'nullable|string|max:5000',
            'goal_scores' => 'nullable|array',
            'goal_scores.*' => 'nullable|integer|min:1|max:5',
        ]);
        $goalScores = $validated['goal_scores'] ?? [];
        try {
            $this->performanceService->submitSelfReview(
                $review->id,
                $validated['self_rating'] ?? null,
                $validated['self_comments'] ?? null,
                $goalScores,
                auth()->id()
            );
        } catch (\DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        if (auth()->user()->employee) {
            return redirect()->route('ess.reviews')->with('success', 'Self review submitted.');
        }
        return redirect()->route('performance.reviews.show', $review)->with('success', 'Self review submitted.');
    }

    public function managerReview(PerformanceReview $review)
    {
        $this->authorize('submitManagerReview', $review);
        $review = $this->performanceService->getReviewWithGoals($review->id);
        $this->performanceService->ensureGoalRatingsForReview($review);
        $review->load('goalRatings.goal');
        return view('performance.reviews.manager-review', compact('review'));
    }

    public function submitManagerReview(Request $request, PerformanceReview $review)
    {
        $this->authorize('submitManagerReview', $review);
        $validated = $request->validate([
            'manager_rating' => 'nullable|integer|min:1|max:5',
            'manager_comments' => 'nullable|string|max:5000',
            'goal_scores' => 'nullable|array',
            'goal_scores.*' => 'nullable|integer|min:1|max:5',
        ]);
        $goalScores = $validated['goal_scores'] ?? [];
        try {
            $this->performanceService->submitManagerReview(
                $review->id,
                $validated['manager_rating'] ?? null,
                $validated['manager_comments'] ?? null,
                $goalScores,
                auth()->id()
            );
        } catch (\DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        return redirect()->route('performance.reviews.index')->with('success', 'Manager review submitted.');
    }

    public function destroy(PerformanceReview $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return redirect()->route('performance.reviews.index')->with('success', 'Review deleted.');
    }
}
