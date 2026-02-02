<?php

namespace App\Modules\Performance\Models;

use App\Models\User;
use App\Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use SoftDeletes;

    protected $table = 'goals';

    protected $fillable = [
        'employee_id',
        'cycle_id',
        'type',
        'title',
        'description',
        'target_value',
        'target_unit',
        'weight',
        'status',
        'due_date',
        'achieved_value',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public const TYPE_KRA = 'kra';
    public const TYPE_OKR = 'okr';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ACHIEVED = 'achieved';
    public const STATUS_NOT_ACHIEVED = 'not_achieved';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(PerformanceReviewCycle::class, 'cycle_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewGoalRatings(): HasMany
    {
        return $this->hasMany(PerformanceReviewGoalRating::class, 'goal_id');
    }
}
