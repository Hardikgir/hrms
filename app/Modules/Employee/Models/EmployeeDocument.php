<?php

namespace App\Modules\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmployeeDocument extends Model
{
    protected $fillable = [
        'employee_id',
        'document_type',
        'file_path',
        'original_filename',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getDocumentUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }
}
