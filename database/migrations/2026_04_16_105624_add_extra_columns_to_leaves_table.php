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
        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('accrued_entitlement')->nullable()->after('total_days');
            $table->string('last_leave_type')->nullable()->after('accrued_entitlement');
            $table->date('last_leave_from')->nullable()->after('last_leave_type');
            $table->date('last_leave_to')->nullable()->after('last_leave_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn(['accrued_entitlement', 'last_leave_type', 'last_leave_from', 'last_leave_to']);
        });
    }
};
