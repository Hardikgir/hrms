<?php

namespace App\Modules\Travel\Models;

use App\Models\User;
use App\Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelRequest extends Model
{
    protected $table = 'travel_requests';

    protected $fillable = [
        'employee_id', 'purpose', 'destination', 'start_date', 'end_date', 'status',
        'estimated_amount', 'actual_amount', 'approved_by', 'approved_at',
        'rejection_reason', 'notes', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
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
