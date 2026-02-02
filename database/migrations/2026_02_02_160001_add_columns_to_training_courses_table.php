<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_courses', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->decimal('duration_hours', 8, 2)->default(0)->after('description');
            $table->string('type', 50)->nullable()->after('duration_hours'); // onboarding, compliance, skill, etc.
            $table->boolean('is_active')->default(true)->after('type');
            $table->foreignId('created_by')->nullable()->after('is_active')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('training_courses', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['name', 'description', 'duration_hours', 'type', 'is_active', 'created_by']);
        });
    }
};
