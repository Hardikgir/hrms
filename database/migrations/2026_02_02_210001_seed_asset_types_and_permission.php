<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage asset types']);

        if (! Schema::hasTable('asset_types') || DB::table('asset_types')->count() > 0) {
            return;
        }

        $types = [
            ['name' => 'Laptop', 'slug' => 'laptop', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Phone', 'slug' => 'phone', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Monitor', 'slug' => 'monitor', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Other', 'slug' => 'other', 'is_active' => true, 'sort_order' => 99],
        ];

        foreach ($types as $t) {
            $t['created_at'] = now();
            $t['updated_at'] = now();
            DB::table('asset_types')->insert($t);
        }
    }

    public function down(): void
    {
        \Spatie\Permission\Models\Permission::where('name', 'manage asset types')->delete();
        DB::table('asset_types')->truncate();
    }
};
