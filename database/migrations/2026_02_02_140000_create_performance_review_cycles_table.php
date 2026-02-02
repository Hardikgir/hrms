<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_review_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('period_start');
            $table->date('period_end');
            $table->string('status')->default('draft'); // draft, active, closed
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->index(['status', 'period_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_review_cycles');
    }
};
