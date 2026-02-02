<?php

namespace App\Modules\Expense\Models;

use App\Models\User;
use App\Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $table = 'expenses';

    protected $fillable = [
        'employee_id', 'amount', 'category', 'description', 'receipt_path', 'status',
        'approved_by', 'approved_at', 'reimbursed_by', 'reimbursed_at',
        'rejected_by', 'rejected_at', 'rejection_reason', 'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'reimbursed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REIMBURSED = 'reimbursed';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reimbursedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reimbursed_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
