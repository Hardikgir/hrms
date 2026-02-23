<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing manager_id data to pivot table
        if (Schema::hasTable('departments') && Schema::hasTable('department_managers')) {
            $departments = DB::table('departments')
                ->whereNotNull('manager_id')
                ->get();
            
            foreach ($departments as $dept) {
                // Check if the relationship doesn't already exist
                $exists = DB::table('department_managers')
                    ->where('department_id', $dept->id)
                    ->where('employee_id', $dept->manager_id)
                    ->exists();
                
                if (!$exists) {
                    DB::table('department_managers')->insert([
                        'department_id' => $dept->id,
                        'employee_id' => $dept->manager_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Optionally reverse: set manager_id from pivot table (but we'll keep both for now)
        // This migration is one-way for safety
    }
};
