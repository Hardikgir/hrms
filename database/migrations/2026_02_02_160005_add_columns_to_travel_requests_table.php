<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            $table->foreignId('employee_id')->after('id')->constrained('employees')->onDelete('cascade');
            $table->string('purpose')->after('employee_id');
            $table->string('destination')->nullable()->after('purpose');
            $table->date('start_date')->after('destination');
            $table->date('end_date')->after('start_date');
            $table->string('status')->default('pending')->after('end_date'); // pending, approved, rejected, completed
            $table->decimal('estimated_amount', 12, 2)->nullable()->after('status');
            $table->decimal('actual_amount', 12, 2)->nullable()->after('estimated_amount');
            $table->foreignId('approved_by')->nullable()->after('actual_amount')->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('approved_at');
            $table->text('notes')->nullable()->after('rejection_reason');
            $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('travel_requests', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'employee_id', 'purpose', 'destination', 'start_date', 'end_date', 'status',
                'estimated_amount', 'actual_amount', 'approved_by', 'approved_at',
                'rejection_reason', 'notes', 'created_by'
            ]);
        });
    }
};
