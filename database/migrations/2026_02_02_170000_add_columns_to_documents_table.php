<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('employee_id')->after('id')->constrained('employees')->cascadeOnDelete();
            $table->string('document_type', 50)->after('employee_id'); // aadhar, pan, bank_passbook, photo
            $table->string('path'); // storage path on disk
            $table->string('original_name')->nullable();
            $table->string('disk', 32)->default('local');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn(['document_type', 'path', 'original_name', 'disk']);
        });
    }
};
