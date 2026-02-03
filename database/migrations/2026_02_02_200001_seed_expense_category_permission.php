<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage expense categories']);
        $superAdmin = \Spatie\Permission\Models\Role::findByName('Super Admin');
        $hrAdmin = \Spatie\Permission\Models\Role::findByName('HR Admin');
        if ($superAdmin && !$superAdmin->hasPermissionTo($permission)) {
            $superAdmin->givePermissionTo($permission);
        }
        if ($hrAdmin && !$hrAdmin->hasPermissionTo($permission)) {
            $hrAdmin->givePermissionTo($permission);
        }
    }

    public function down(): void
    {
        \Spatie\Permission\Models\Permission::where('name', 'manage expense categories')->delete();
    }
};
