<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        $permission = Permission::firstOrCreate(['name' => 'manage roles']);
    }

    public function down(): void
    {
        Permission::where('name', 'manage roles')->delete();
    }
};
