<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('designations', function (Blueprint $row) {
            $row->string('sidebar_color')->nullable()->after('max_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('designations', function (Blueprint $row) {
            $row->dropColumn('sidebar_color');
        });
    }
};
