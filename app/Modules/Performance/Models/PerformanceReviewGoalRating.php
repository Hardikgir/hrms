<?php

namespace App\Modules\Performance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReviewGoalRating extends Model
{
    protected $table = 'performance_review_goal_ratings';

    protected $fillable = [
        'performance_review_id',
        'goal_id',
        'self_score',
        'manager_score',
        'comment',
    ];

    public function performanceReview(): BelongsTo
    {
        return $this->belongsTo(PerformanceReview::class, 'performance_review_id');
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
