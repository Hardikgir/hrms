<?php

namespace App\Models;

use App\Modules\Employee\Models\Department;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const ROLE_TYPE_ADMIN = 'admin';
    public const ROLE_TYPE_MANAGER = 'manager';
    public const ROLE_TYPE_EMPLOYEE = 'employee';

    protected $fillable = [
        'name',
        'guard_name',
        'department_id',
        'role_type',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public static function roleTypes(): array
    {
        return [
            self::ROLE_TYPE_ADMIN    => 'Admin',
            self::ROLE_TYPE_MANAGER  => 'Manager',
            self::ROLE_TYPE_EMPLOYEE  => 'Employee',
        ];
    }

    /**
     * Display name for role type (e.g. for "HR" + "Admin" -> "HR Admin").
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->department_id && $this->role_type) {
            $dept = $this->department?->name ?? 'Department';
            $type = self::roleTypes()[$this->role_type] ?? ucfirst($this->role_type);
            return "{$dept} {$type}";
        }
        return $this->name;
    }
}
