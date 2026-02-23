<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Change employment_type from enum to string to store slug
            $table->string('employment_type', 100)->default('full_time')->change();
            
            // Change employment_status from enum to string to store slug
            $table->string('employment_status', 100)->default('active')->change();
            
            // Add indexes
            $table->index('employment_type');
            $table->index('employment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['employment_type']);
            $table->dropIndex(['employment_status']);
            
            // Revert back to enum (this might fail if data doesn't match enum values)
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern', 'temporary'])->default('full_time')->change();
            $table->enum('employment_status', ['active', 'inactive', 'terminated', 'resigned', 'on_leave'])->default('active')->change();
        });
    }
};
