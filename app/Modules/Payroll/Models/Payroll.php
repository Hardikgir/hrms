<?php

namespace App\Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Employee\Models\Employee;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'employee_id',
        'year',
        'month',
        'pay_period_start',
        'pay_period_end',
        'working_days',
        'present_days',
        'leave_days',
        'absent_days',
        'basic_salary',
        'earnings',
        'deductions',
        'statutory',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'status',
        'paid_date',
        'notes',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'working_days' => 'integer',
        'present_days' => 'integer',
        'leave_days' => 'integer',
        'absent_days' => 'integer',
        'basic_salary' => 'decimal:2',
        'earnings' => 'array',
        'deductions' => 'array',
        'statutory' => 'array',
        'gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(PayrollAuditLog::class);
    }
}

