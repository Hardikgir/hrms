<?php

namespace App\Modules\Training\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingCourse extends Model
{
    protected $table = 'training_courses';

    protected $fillable = ['name', 'description', 'duration_hours', 'type', 'is_active', 'created_by'];

    protected $casts = [
        'duration_hours' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TrainingAssignment::class, 'training_course_id');
    }
}
