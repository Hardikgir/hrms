<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('type', 50)->after('name'); // laptop, phone, etc.
            $table->string('serial_number')->nullable()->after('type');
            $table->string('asset_tag')->nullable()->after('serial_number');
            $table->foreignId('employee_id')->nullable()->after('asset_tag')->constrained('employees')->onDelete('set null');
            $table->string('status')->default('available')->after('employee_id'); // available, assigned, under_maintenance, retired
            $table->date('purchase_date')->nullable()->after('status');
            $table->decimal('purchase_value', 12, 2)->nullable()->after('purchase_date');
            $table->string('location')->nullable()->after('purchase_value');
            $table->text('notes')->nullable()->after('location');
            $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'name', 'type', 'serial_number', 'asset_tag', 'employee_id', 'status',
                'purchase_date', 'purchase_value', 'location', 'notes', 'created_by'
            ]);
        });
    }
};
