<?php

namespace App\Modules\Leave\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveExtraDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_id',
        'is_hospital_paid',
        'is_employee_paid',
        'family_visa_details',
        'contact_address',
        'contact_phone',
        'contact_mobile',
        'contact_email',
    ];

    protected $casts = [
        'is_hospital_paid' => 'boolean',
        'is_employee_paid' => 'boolean',
        'family_visa_details' => 'array',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }
}
