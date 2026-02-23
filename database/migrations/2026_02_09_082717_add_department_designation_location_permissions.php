<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['manage departments', 'manage designations', 'manage locations'] as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }
    }

    public function down(): void
    {
        Permission::whereIn('name', ['manage departments', 'manage designations', 'manage locations'])->delete();
    }
};
