<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('performance_review_goal_ratings')) {
            Schema::create('performance_review_goal_ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('performance_review_id')->constrained('performance_reviews')->onDelete('cascade');
                $table->foreignId('goal_id')->constrained('goals')->onDelete('cascade');
                $table->unsignedTinyInteger('self_score')->nullable();
                $table->unsignedTinyInteger('manager_score')->nullable();
                $table->text('comment')->nullable();
                $table->timestamps();
                $table->unique(['performance_review_id', 'goal_id'], 'pr_goal_ratings_review_goal_unique');
            });
        } else {
            Schema::table('performance_review_goal_ratings', function (Blueprint $table) {
                $table->unique(['performance_review_id', 'goal_id'], 'pr_goal_ratings_review_goal_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_review_goal_ratings');
    }
};
