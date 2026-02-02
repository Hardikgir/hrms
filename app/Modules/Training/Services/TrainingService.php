<?php

namespace App\Modules\Training\Services;

use App\Modules\Training\Models\TrainingAssignment;
use App\Modules\Training\Models\TrainingCourse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TrainingService
{
    public function listCourses(?string $type = null, bool $activeOnly = true): LengthAwarePaginator
    {
        $query = TrainingCourse::with('createdBy')->latest();
        if ($type) {
            $query->where('type', $type);
        }
        if ($activeOnly) {
            $query->where('is_active', true);
        }
        return $query->paginate(15);
    }

    public function createCourse(array $data, ?int $createdBy = null): TrainingCourse
    {
        $data['created_by'] = $createdBy;
        return TrainingCourse::create($data);
    }

    public function updateCourse(TrainingCourse $course, array $data): TrainingCourse
    {
        $course->update($data);
        return $course->fresh();
    }

    public function listAssignments(?int $employeeId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = TrainingAssignment::with(['employee', 'course', 'assignedBy'])->latest();
        if ($employeeId !== null) {
            $query->where('employee_id', $employeeId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        return $query->paginate(15);
    }

    public function assign(int $employeeId, int $courseId, ?string $dueDate = null, ?int $assignedBy = null): TrainingAssignment
    {
        $exists = TrainingAssignment::where('employee_id', $employeeId)
            ->where('training_course_id', $courseId)
            ->whereIn('status', [TrainingAssignment::STATUS_ASSIGNED, TrainingAssignment::STATUS_IN_PROGRESS])
            ->exists();
        if ($exists) {
            throw new \DomainException('Employee already has an active assignment for this course.');
        }
        return TrainingAssignment::create([
            'employee_id' => $employeeId,
            'training_course_id' => $courseId,
            'status' => TrainingAssignment::STATUS_ASSIGNED,
            'assigned_at' => now()->toDateString(),
            'due_date' => $dueDate,
            'assigned_by' => $assignedBy,
        ]);
    }

    public function markCompleted(int $assignmentId, ?int $score = null): TrainingAssignment
    {
        $assignment = TrainingAssignment::findOrFail($assignmentId);
        $assignment->update([
            'status' => TrainingAssignment::STATUS_COMPLETED,
            'completed_at' => now()->toDateString(),
            'score' => $score,
        ]);
        return $assignment->fresh();
    }

    public function getAssignmentsForEmployee(int $employeeId): Collection
    {
        return TrainingAssignment::with('course')
            ->where('employee_id', $employeeId)
            ->latest()
            ->get();
    }
}
