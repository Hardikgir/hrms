<?php

namespace App\Modules\Payroll\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollAuditLog extends Model
{
    protected $fillable = [
        'payroll_id',
        'action',
        'user_id',
        'old_status',
        'new_status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
