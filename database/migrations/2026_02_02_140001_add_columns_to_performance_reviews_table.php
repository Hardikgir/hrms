<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('performance_reviews', function (Blueprint $table) {
            $table->foreignId('employee_id')->after('id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('reviewer_id')->nullable()->after('employee_id')->constrained('users')->onDelete('set null');
            $table->foreignId('cycle_id')->after('reviewer_id')->constrained('performance_review_cycles')->onDelete('cascade');
            $table->string('status')->default('pending')->after('cycle_id'); // pending, self_review, manager_review, completed
            $table->unsignedTinyInteger('self_rating')->nullable()->after('status'); // 1-5
            $table->unsignedTinyInteger('manager_rating')->nullable()->after('self_rating'); // 1-5
            $table->unsignedTinyInteger('overall_rating')->nullable()->after('manager_rating'); // 1-5 final
            $table->text('self_comments')->nullable()->after('overall_rating');
            $table->text('manager_comments')->nullable()->after('self_comments');
            $table->timestamp('submitted_at')->nullable()->after('manager_comments');
            $table->timestamp('completed_at')->nullable()->after('submitted_at');
            $table->foreignId('created_by')->nullable()->after('completed_at')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('performance_reviews', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['reviewer_id']);
            $table->dropForeign(['cycle_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'employee_id', 'reviewer_id', 'cycle_id', 'status',
                'self_rating', 'manager_rating', 'overall_rating',
                'self_comments', 'manager_comments', 'submitted_at', 'completed_at', 'created_by'
            ]);
        });
    }
};
