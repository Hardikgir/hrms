<?php

namespace App\Modules\Performance\Models;

use App\Models\User;
use App\Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceReview extends Model
{
    protected $table = 'performance_reviews';

    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'cycle_id',
        'status',
        'self_rating',
        'manager_rating',
        'overall_rating',
        'self_comments',
        'manager_comments',
        'submitted_at',
        'completed_at',
        'created_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_SELF_REVIEW = 'self_review';
    public const STATUS_MANAGER_REVIEW = 'manager_review';
    public const STATUS_COMPLETED = 'completed';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(PerformanceReviewCycle::class, 'cycle_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function goalRatings(): HasMany
    {
        return $this->hasMany(PerformanceReviewGoalRating::class, 'performance_review_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canSubmitSelfReview(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_SELF_REVIEW]);
    }

    public function canSubmitManagerReview(): bool
    {
        return in_array($this->status, [self::STATUS_SELF_REVIEW, self::STATUS_MANAGER_REVIEW]);
    }
}
