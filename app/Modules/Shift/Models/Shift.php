<?php

namespace App\Modules\Shift\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'start_time',
        'end_time',
        'break_duration',
        'working_hours',
        'is_flexible',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'break_duration' => 'integer',
        'working_hours' => 'integer',
        'is_flexible' => 'boolean',
        'is_active' => 'boolean',
    ];
}

