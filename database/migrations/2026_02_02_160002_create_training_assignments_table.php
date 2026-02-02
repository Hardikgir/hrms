<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('training_course_id')->constrained('training_courses')->onDelete('cascade');
            $table->string('status')->default('assigned'); // assigned, in_progress, completed
            $table->date('assigned_at')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->unsignedTinyInteger('score')->nullable(); // 0-100
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->index(['employee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_assignments');
    }
};
