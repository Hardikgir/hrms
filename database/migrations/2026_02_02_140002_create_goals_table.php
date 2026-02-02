<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('cycle_id')->nullable()->constrained('performance_review_cycles')->onDelete('set null');
            $table->string('type'); // kra, okr
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('target_value')->nullable(); // e.g. "100%", "10 units"
            $table->string('target_unit')->nullable(); // percent, count, etc.
            $table->unsignedTinyInteger('weight')->default(100); // weight for aggregation (e.g. 1-100)
            $table->string('status')->default('active'); // draft, active, achieved, not_achieved
            $table->date('due_date')->nullable();
            $table->string('achieved_value')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['employee_id', 'cycle_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
