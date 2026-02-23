<?php

namespace App\Modules\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class Designation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'description',
        'department_id',
        'min_salary',
        'max_salary',
        'sidebar_color',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Permissions attached to this designation (designation acts as role).
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'designation_has_permissions',
            'designation_id',
            'permission_id'
        );
    }

    /**
     * Sync permissions by IDs (same pattern as Spatie Role).
     */
    public function syncPermissions(iterable $permissions): static
    {
        $ids = collect($permissions)->map(function ($p) {
            return $p instanceof Permission ? $p->id : $p;
        })->all();
        $this->permissions()->sync($ids);
        return $this;
    }

    /**
     * Whether this designation has the given permission (by name or model).
     */
    public function hasPermissionTo(string|\Spatie\Permission\Contracts\Permission $permission, string $guardName = 'web'): bool
    {
        if ($permission instanceof \Spatie\Permission\Contracts\Permission) {
            return $this->permissions->contains('id', $permission->id);
        }
        if ($this->relationLoaded('permissions')) {
            return $this->permissions->where('name', $permission)->where('guard_name', $guardName)->isNotEmpty();
        }
        return $this->permissions()->where('name', $permission)->where('guard_name', $guardName)->exists();
    }
}

