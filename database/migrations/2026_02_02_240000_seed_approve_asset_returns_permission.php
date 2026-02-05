<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'approve asset returns']);
        $superAdmin = \Spatie\Permission\Models\Role::findByName('Super Admin');
        if ($superAdmin && !$superAdmin->hasPermissionTo($permission)) {
            $superAdmin->givePermissionTo($permission);
        }
    }

    public function down(): void
    {
        \Spatie\Permission\Models\Permission::where('name', 'approve asset returns')->delete();
    }
};
