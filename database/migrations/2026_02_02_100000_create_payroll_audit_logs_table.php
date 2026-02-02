<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->string('action'); // run, lock, approve, pay, cancel, unlock
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->json('payload')->nullable(); // extra context
            $table->timestamps();

            $table->index(['payroll_id', 'created_at']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_audit_logs');
    }
};
