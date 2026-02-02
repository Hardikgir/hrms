<?php

namespace App\Modules\Asset\Models;

use App\Models\User;
use App\Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $table = 'assets';

    protected $fillable = [
        'name', 'type', 'serial_number', 'asset_tag', 'employee_id', 'status',
        'purchase_date', 'purchase_value', 'location', 'notes', 'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_value' => 'decimal:2',
    ];

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_UNDER_MAINTENANCE = 'under_maintenance';
    public const STATUS_RETIRED = 'retired';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
