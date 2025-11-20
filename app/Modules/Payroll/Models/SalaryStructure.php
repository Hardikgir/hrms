<?php

namespace App\Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'components',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'components' => 'array',
        'is_active' => 'boolean',
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}

