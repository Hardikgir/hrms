<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('guard_name');
            $table->string('role_type', 50)->nullable()->after('department_id')->comment('admin, manager, employee');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });

        // Link existing HR roles to HR department
        $hrDept = \App\Modules\Employee\Models\Department::where('code', 'HR')->first();
        if ($hrDept) {
            $map = [
                'HR Admin' => ['department_id' => $hrDept->id, 'role_type' => 'admin'],
                'HR Manager' => ['department_id' => $hrDept->id, 'role_type' => 'manager'],
                'HR Employee' => ['department_id' => $hrDept->id, 'role_type' => 'employee'],
            ];
            foreach ($map as $name => $attrs) {
                \DB::table('roles')->where('name', $name)->where('guard_name', 'web')->update($attrs);
            }
        }
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'role_type']);
        });
    }
};
