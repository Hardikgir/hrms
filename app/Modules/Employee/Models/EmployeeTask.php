<?php

namespace App\Modules\Employee\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeTask extends Model
{
    protected $table = 'employee_tasks';

    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'status',
        'due_date',
        'priority',
        'action_route',
        'action_label',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where(function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId)->orWhereNull('employee_id');
        });
    }
}
