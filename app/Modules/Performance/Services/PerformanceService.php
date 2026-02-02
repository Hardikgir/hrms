<?php

namespace App\Modules\Performance\Services;

use App\Modules\Employee\Models\Employee;
use App\Modules\Performance\Models\Goal;
use App\Modules\Performance\Models\PerformanceReview;
use App\Modules\Performance\Models\PerformanceReviewCycle;
use App\Modules\Performance\Models\PerformanceReviewGoalRating;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class PerformanceService
{
    public function listCycles(?string $status = null): LengthAwarePaginator
    {
        $query = PerformanceReviewCycle::with('createdBy')->latest('period_start');
        if ($status) {
            $query->where('status', $status);
        }
        return $query->paginate(15);
    }

    public function createCycle(
        string $name,
        string $periodStart,
        string $periodEnd,
        ?int $createdBy = null
    ): PerformanceReviewCycle {
        $start = Carbon::parse($periodStart);
        $end = Carbon::parse($periodEnd);
        if ($end->lt($start)) {
            throw new \InvalidArgumentException('Period end must be on or after period start.');
        }
        return PerformanceReviewCycle::create([
            'name' => $name,
            'period_start' => $start->toDateString(),
            'period_end' => $end->toDateString(),
            'status' => 'draft',
            'created_by' => $createdBy,
        ]);
    }

    public function updateCycle(
        PerformanceReviewCycle $cycle,
        array $data,
        ?int $updatedBy = null
    ): PerformanceReviewCycle {
        if ($cycle->isClosed()) {
            throw new \DomainException('Cannot update a closed cycle.');
        }
        if (isset($data['period_start'])) {
            $cycle->period_start = Carbon::parse($data['period_start'])->toDateString();
        }
        if (isset($data['period_end'])) {
            $cycle->period_end = Carbon::parse($data['period_end'])->toDateString();
        }
        if (isset($data['name'])) {
            $cycle->name = $data['name'];
        }
        if (isset($data['status'])) {
            if ($data['status'] === 'closed' && !$cycle->isClosed()) {
                $cycle->status = 'closed';
            } elseif (in_array($data['status'], ['draft', 'active'])) {
                $cycle->status = $data['status'];
            }
        }
        $cycle->save();
        return $cycle->fresh();
    }

    public function listGoals(int $employeeId = null, ?int $cycleId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = Goal::with(['employee', 'cycle', 'createdBy'])->latest();
        if ($employeeId !== null) {
            $query->where('employee_id', $employeeId);
        }
        if ($cycleId !== null) {
            $query->where('cycle_id', $cycleId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        return $query->paginate(20);
    }

    public function createGoal(
        int $employeeId,
        string $type,
        string $title,
        array $attributes = [],
        ?int $createdBy = null
    ): Goal {
        if (!in_array($type, [Goal::TYPE_KRA, Goal::TYPE_OKR])) {
            throw new \InvalidArgumentException('Goal type must be kra or okr.');
        }
        $data = [
            'employee_id' => $employeeId,
            'type' => $type,
            'title' => $title,
            'description' => $attributes['description'] ?? null,
            'target_value' => $attributes['target_value'] ?? null,
            'target_unit' => $attributes['target_unit'] ?? null,
            'weight' => (int) ($attributes['weight'] ?? 100),
            'status' => $attributes['status'] ?? Goal::STATUS_ACTIVE,
            'due_date' => isset($attributes['due_date']) ? Carbon::parse($attributes['due_date'])->toDateString() : null,
            'cycle_id' => $attributes['cycle_id'] ?? null,
            'created_by' => $createdBy,
        ];
        return Goal::create($data);
    }

    public function updateGoal(Goal $goal, array $data): Goal
    {
        $goal->update($data);
        return $goal->fresh();
    }

    public function getGoalsForEmployeeInCycle(int $employeeId, ?int $cycleId): Collection
    {
        $query = Goal::where('employee_id', $employeeId)->where('status', '!=', 'draft');
        if ($cycleId) {
            $query->where('cycle_id', $cycleId);
        } else {
            $query->whereNull('cycle_id');
        }
        return $query->orderBy('type')->orderBy('id')->get();
    }

    public function createReview(
        int $employeeId,
        int $cycleId,
        ?int $reviewerId = null,
        ?int $createdBy = null
    ): PerformanceReview {
        $cycle = PerformanceReviewCycle::findOrFail($cycleId);
        if (!$cycle->isActive() && !$cycle->isDraft()) {
            throw new \DomainException('Reviews can only be created for active or draft cycles.');
        }
        $employee = Employee::findOrFail($employeeId);
        $reviewerId = $reviewerId ?? $this->resolveReviewerForEmployee($employee);
        $exists = PerformanceReview::where('employee_id', $employeeId)->where('cycle_id', $cycleId)->exists();
        if ($exists) {
            throw new \DomainException('A review for this employee in this cycle already exists.');
        }
        return PerformanceReview::create([
            'employee_id' => $employeeId,
            'reviewer_id' => $reviewerId,
            'cycle_id' => $cycleId,
            'status' => PerformanceReview::STATUS_PENDING,
            'created_by' => $createdBy,
        ]);
    }

    protected function resolveReviewerForEmployee(Employee $employee): ?int
    {
        if ($employee->manager_id) {
            return \App\Models\User::where('employee_id', $employee->manager_id)->value('id');
        }
        return null;
    }

    public function getReviewWithGoals(int $reviewId): PerformanceReview
    {
        return PerformanceReview::with([
            'employee', 'reviewer', 'cycle',
            'goalRatings.goal',
        ])->findOrFail($reviewId);
    }

    public function ensureGoalRatingsForReview(PerformanceReview $review): void
    {
        $goals = $this->getGoalsForEmployeeInCycle($review->employee_id, $review->cycle_id);
        foreach ($goals as $goal) {
            PerformanceReviewGoalRating::firstOrCreate(
                [
                    'performance_review_id' => $review->id,
                    'goal_id' => $goal->id,
                ],
                ['performance_review_id' => $review->id, 'goal_id' => $goal->id]
            );
        }
    }

    public function submitSelfReview(
        int $reviewId,
        ?int $selfRating,
        ?string $selfComments,
        array $goalScores,
        ?int $submittedBy = null
    ): PerformanceReview {
        $review = PerformanceReview::findOrFail($reviewId);
        if (!$review->canSubmitSelfReview()) {
            throw new \DomainException('Self review cannot be submitted for this review.');
        }
        $review->update([
            'self_rating' => $selfRating,
            'self_comments' => $selfComments,
            'status' => PerformanceReview::STATUS_MANAGER_REVIEW,
            'submitted_at' => now(),
        ]);
        foreach ($goalScores as $goalId => $score) {
            PerformanceReviewGoalRating::where('performance_review_id', $review->id)
                ->where('goal_id', $goalId)
                ->update(['self_score' => $score]);
        }
        return $review->fresh();
    }

    public function submitManagerReview(
        int $reviewId,
        ?int $managerRating,
        ?string $managerComments,
        array $goalScores,
        ?int $submittedBy = null
    ): PerformanceReview {
        $review = PerformanceReview::findOrFail($reviewId);
        if (!$review->canSubmitManagerReview()) {
            throw new \DomainException('Manager review cannot be submitted for this review.');
        }
        $goalRatings = $review->goalRatings;
        $weightedSum = 0;
        $weightTotal = 0;
        foreach ($goalRatings as $gr) {
            $score = $goalScores[$gr->goal_id] ?? $gr->manager_score;
            if ($gr->goal) {
                $weightTotal += $gr->goal->weight;
                $weightedSum += ($score ?? 0) * $gr->goal->weight;
            }
        }
        $overall = $weightTotal > 0 ? round($weightedSum / $weightTotal) : $managerRating;

        foreach ($goalScores as $goalId => $score) {
            PerformanceReviewGoalRating::where('performance_review_id', $review->id)
                ->where('goal_id', $goalId)
                ->update(['manager_score' => $score]);
        }

        $review->update([
            'manager_rating' => $managerRating,
            'manager_comments' => $managerComments,
            'overall_rating' => $overall,
            'status' => PerformanceReview::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
        return $review->fresh();
    }

    public function listReviews(?int $employeeId = null, ?int $cycleId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = PerformanceReview::with(['employee', 'reviewer', 'cycle'])->latest();
        if ($employeeId !== null) {
            $query->where('employee_id', $employeeId);
        }
        if ($cycleId !== null) {
            $query->where('cycle_id', $cycleId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        return $query->paginate(15);
    }

    public function getReviewsForEmployee(int $employeeId): Collection
    {
        return PerformanceReview::with(['cycle', 'reviewer'])
            ->where('employee_id', $employeeId)
            ->latest()
            ->get();
    }

    /** Reviews where the current user is the assigned reviewer */
    public function getReviewsToCompleteForUser(int $userId): Collection
    {
        return PerformanceReview::with(['employee', 'cycle'])
            ->where('reviewer_id', $userId)
            ->whereIn('status', [PerformanceReview::STATUS_MANAGER_REVIEW, PerformanceReview::STATUS_SELF_REVIEW, PerformanceReview::STATUS_PENDING])
            ->latest()
            ->get();
    }
}
