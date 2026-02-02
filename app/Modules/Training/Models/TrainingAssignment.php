<?php

namespace App\Modules\Training\Models;

use App\Models\User;
use App\Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAssignment extends Model
{
    protected $table = 'training_assignments';

    protected $fillable = [
        'employee_id', 'training_course_id', 'status', 'assigned_at', 'due_date',
        'completed_at', 'score', 'assigned_by',
    ];

    protected $casts = [
        'assigned_at' => 'date',
        'due_date' => 'date',
        'completed_at' => 'date',
    ];

    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(TrainingCourse::class, 'training_course_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
