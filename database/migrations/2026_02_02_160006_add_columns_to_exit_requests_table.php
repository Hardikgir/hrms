<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exit_requests', function (Blueprint $table) {
            $table->foreignId('employee_id')->after('id')->constrained('employees')->onDelete('cascade');
            $table->date('resignation_date')->after('employee_id');
            $table->date('last_working_date')->after('resignation_date');
            $table->string('reason')->nullable()->after('last_working_date'); // voluntary, retirement, etc.
            $table->text('reason_details')->nullable()->after('reason');
            $table->string('status')->default('pending')->after('reason_details'); // pending, clearance, exit_interview, settlement, completed
            $table->text('exit_interview_notes')->nullable()->after('status');
            $table->decimal('settlement_amount', 12, 2)->nullable()->after('exit_interview_notes');
            $table->date('settlement_paid_at')->nullable()->after('settlement_amount');
            $table->timestamp('clearance_completed_at')->nullable()->after('settlement_paid_at');
            $table->json('checklist')->nullable()->after('clearance_completed_at'); // {it: true, hr: false, ...}
            $table->foreignId('approved_by')->nullable()->after('checklist')->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->after('approved_by')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('exit_requests', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'employee_id', 'resignation_date', 'last_working_date', 'reason', 'reason_details', 'status',
                'exit_interview_notes', 'settlement_amount', 'settlement_paid_at', 'clearance_completed_at',
                'checklist', 'approved_by', 'created_by'
            ]);
        });
    }
};
