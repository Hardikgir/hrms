<?php

namespace App\Models;

use App\Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'employee_id',
        'document_type',
        'path',
        'original_name',
        'disk',
    ];

    protected $casts = [
        'employee_id' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public static function typeLabels(): array
    {
        return [
            'aadhar' => 'Aadhar Card',
            'pan' => 'PAN Card',
            'bank_passbook' => 'Bank Passbook / Cancelled cheque',
            'photo' => 'Passport size photo',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabels()[$this->document_type] ?? $this->document_type;
    }
}
