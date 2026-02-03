<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_tasks', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->after('completed_at')->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });

        // Add 'approved' to status enum (MySQL only; SQLite uses string)
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement("ALTER TABLE employee_tasks MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'approved') DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        Schema::table('employee_tasks', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['completed_at', 'approved_by', 'approved_at']);
        });
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement("ALTER TABLE employee_tasks MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending'");
        }
    }
};
