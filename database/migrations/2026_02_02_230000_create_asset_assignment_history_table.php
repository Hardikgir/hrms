<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_assignment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->timestamp('assigned_at');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('returned_at')->nullable();
            $table->foreignId('returned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Backfill: one open history row per currently assigned asset
        $assigned = DB::table('assets')->whereNotNull('employee_id')->get(['id', 'employee_id', 'updated_at']);
        foreach ($assigned as $row) {
            DB::table('asset_assignment_history')->insert([
                'asset_id' => $row->id,
                'employee_id' => $row->employee_id,
                'assigned_at' => $row->updated_at,
                'assigned_by' => null,
                'returned_at' => null,
                'returned_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_assignment_history');
    }
};
