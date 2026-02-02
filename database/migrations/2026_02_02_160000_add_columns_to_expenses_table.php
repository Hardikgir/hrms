<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('employee_id')->after('id')->constrained('employees')->onDelete('cascade');
            $table->decimal('amount', 12, 2)->after('employee_id');
            $table->string('category', 100)->after('amount'); // travel, meals, supplies, etc.
            $table->string('description')->nullable()->after('category');
            $table->string('receipt_path')->nullable()->after('description');
            $table->string('status')->default('pending')->after('receipt_path'); // pending, approved, rejected, reimbursed
            $table->foreignId('approved_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->foreignId('reimbursed_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
            $table->timestamp('reimbursed_at')->nullable()->after('reimbursed_by');
            $table->foreignId('rejected_by')->nullable()->after('reimbursed_at')->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            $table->foreignId('created_by')->nullable()->after('rejection_reason')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['reimbursed_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'employee_id', 'amount', 'category', 'description', 'receipt_path', 'status',
                'approved_by', 'approved_at', 'reimbursed_by', 'reimbursed_at',
                'rejected_by', 'rejected_at', 'rejection_reason', 'created_by'
            ]);
        });
    }
};
