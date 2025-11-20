<?php

namespace App\Modules\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'department_id',
        'designation_id',
        'location_id',
        'manager_id',
        'joining_date',
        'confirmation_date',
        'exit_date',
        'employment_type',
        'employment_status',
        'ctc',
        'salary_structure',
        'bank_name',
        'bank_account_number',
        'bank_ifsc',
        'bank_branch',
        'pan_number',
        'aadhar_number',
        'passport_number',
        'passport_expiry',
        'profile_picture',
        'bio',
        'skills',
        'certifications',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'confirmation_date' => 'date',
        'exit_date' => 'date',
        'passport_expiry' => 'date',
        'salary_structure' => 'array',
        'skills' => 'array',
        'certifications' => 'array',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'bank_account_number',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

