<?php

namespace App\Modules\Exit\Models;

use App\Models\User;
use App\Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExitRequest extends Model
{
    protected $table = 'exit_requests';

    protected $fillable = [
        'employee_id', 'resignation_date', 'last_working_date', 'reason', 'reason_details',
        'status', 'exit_interview_notes', 'settlement_amount', 'settlement_paid_at',
        'clearance_completed_at', 'checklist', 'approved_by', 'created_by',
    ];

    protected $casts = [
        'resignation_date' => 'date',
        'last_working_date' => 'date',
        'settlement_amount' => 'decimal:2',
        'settlement_paid_at' => 'date',
        'clearance_completed_at' => 'datetime',
        'checklist' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_CLEARANCE = 'clearance';
    public const STATUS_EXIT_INTERVIEW = 'exit_interview';
    public const STATUS_SETTLEMENT = 'settlement';
    public const STATUS_COMPLETED = 'completed';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
