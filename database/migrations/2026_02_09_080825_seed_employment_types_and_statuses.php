<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create permissions
        $permission1 = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage employment types']);
        $permission2 = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage employment statuses']);

        // Seed employment types
        if (Schema::hasTable('employment_types') && DB::table('employment_types')->count() == 0) {
            $types = [
                ['name' => 'Full Time', 'slug' => 'full_time', 'is_active' => true, 'sort_order' => 1],
                ['name' => 'Part Time', 'slug' => 'part_time', 'is_active' => true, 'sort_order' => 2],
                ['name' => 'Contract', 'slug' => 'contract', 'is_active' => true, 'sort_order' => 3],
                ['name' => 'Intern', 'slug' => 'intern', 'is_active' => true, 'sort_order' => 4],
                ['name' => 'Temporary', 'slug' => 'temporary', 'is_active' => true, 'sort_order' => 5],
            ];

            foreach ($types as $t) {
                $t['created_at'] = now();
                $t['updated_at'] = now();
                DB::table('employment_types')->insert($t);
            }
        }

        // Seed employment statuses
        if (Schema::hasTable('employment_statuses') && DB::table('employment_statuses')->count() == 0) {
            $statuses = [
                ['name' => 'Active', 'slug' => 'active', 'is_active' => true, 'sort_order' => 1],
                ['name' => 'Inactive', 'slug' => 'inactive', 'is_active' => true, 'sort_order' => 2],
                ['name' => 'Terminated', 'slug' => 'terminated', 'is_active' => true, 'sort_order' => 3],
                ['name' => 'Resigned', 'slug' => 'resigned', 'is_active' => true, 'sort_order' => 4],
                ['name' => 'On Leave', 'slug' => 'on_leave', 'is_active' => true, 'sort_order' => 5],
            ];

            foreach ($statuses as $s) {
                $s['created_at'] = now();
                $s['updated_at'] = now();
                DB::table('employment_statuses')->insert($s);
            }
        }

        // Update existing employees to use slug values
        if (Schema::hasTable('employees')) {
            // Update employment_type values to slugs if they're still enum values
            DB::table('employees')->where('employment_type', 'full_time')->update(['employment_type' => 'full_time']);
            DB::table('employees')->where('employment_type', 'part_time')->update(['employment_type' => 'part_time']);
            DB::table('employees')->where('employment_type', 'contract')->update(['employment_type' => 'contract']);
            DB::table('employees')->where('employment_type', 'intern')->update(['employment_type' => 'intern']);
            DB::table('employees')->where('employment_type', 'temporary')->update(['employment_type' => 'temporary']);

            // Update employment_status values to slugs if they're still enum values
            DB::table('employees')->where('employment_status', 'active')->update(['employment_status' => 'active']);
            DB::table('employees')->where('employment_status', 'inactive')->update(['employment_status' => 'inactive']);
            DB::table('employees')->where('employment_status', 'terminated')->update(['employment_status' => 'terminated']);
            DB::table('employees')->where('employment_status', 'resigned')->update(['employment_status' => 'resigned']);
            DB::table('employees')->where('employment_status', 'on_leave')->update(['employment_status' => 'on_leave']);
        }
    }

    public function down(): void
    {
        \Spatie\Permission\Models\Permission::where('name', 'manage employment types')->delete();
        \Spatie\Permission\Models\Permission::where('name', 'manage employment statuses')->delete();
        DB::table('employment_types')->truncate();
        DB::table('employment_statuses')->truncate();
    }
};
