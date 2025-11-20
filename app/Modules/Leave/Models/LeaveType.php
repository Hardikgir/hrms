<?php

namespace App\Modules\Leave\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'description',
        'max_days_per_year',
        'is_paid',
        'requires_approval',
        'can_carry_forward',
        'carry_forward_limit',
        'is_active',
        'accrual_rules',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'max_days_per_year' => 'integer',
        'is_paid' => 'boolean',
        'requires_approval' => 'boolean',
        'can_carry_forward' => 'boolean',
        'carry_forward_limit' => 'integer',
        'is_active' => 'boolean',
        'accrual_rules' => 'array',
    ];
}

