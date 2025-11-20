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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->year('year');
            $table->tinyInteger('month'); // 1-12
            $table->date('pay_period_start');
            $table->date('pay_period_end');
            $table->integer('working_days');
            $table->integer('present_days');
            $table->integer('leave_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->decimal('basic_salary', 12, 2);
            $table->json('earnings')->nullable(); // All earnings breakdown
            $table->json('deductions')->nullable(); // All deductions breakdown
            $table->json('statutory')->nullable(); // PF, ESI, PT, TDS
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('total_deductions', 12, 2);
            $table->decimal('net_salary', 12, 2);
            $table->enum('status', ['draft', 'processed', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            $table->unique(['employee_id', 'year', 'month']);
            $table->index(['year', 'month', 'status']);
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
