<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Add HR approval fields
            $table->foreignId('hr_approved_by')->nullable()->after('approved_by')->constrained('users')->onDelete('set null');
            $table->timestamp('hr_approved_at')->nullable()->after('approved_at');
        });

        // Update enum to include hr_approved status
        // MySQL doesn't support ALTER ENUM directly, so we need to modify the column
        DB::statement("ALTER TABLE `leaves` MODIFY COLUMN `status` ENUM('pending', 'hr_approved', 'approved', 'rejected', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['hr_approved_by']);
            $table->dropColumn(['hr_approved_by', 'hr_approved_at']);
        });

        // Revert enum
        DB::statement("ALTER TABLE `leaves` MODIFY COLUMN `status` ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending'");
    }
};
