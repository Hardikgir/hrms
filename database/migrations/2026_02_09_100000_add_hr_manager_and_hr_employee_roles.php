<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Add HR Manager and HR Employee roles and assign permissions.
     * HR Admin already exists; ensure all three HR designations (roles) are available.
     */
    public function up(): void
    {
        foreach (['HR Manager', 'HR Employee'] as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $hrManager = Role::findByName('HR Manager', 'web');
        $hrManager->syncPermissions([
            'view employees',
            'view attendance', 'create attendance', 'update attendance',
            'view leaves', 'approve leaves',
            'manage tasks',
            'view performance', 'manage performance',
            'view expenses', 'approve expenses',
            'view training',
            'view shifts', 'manage shifts',
            'view assets', 'approve asset returns',
            'view travel', 'approve travel',
            'view exit', 'manage exit',
            'view payroll',
        ]);

        $hrEmployee = Role::findByName('HR Employee', 'web');
        $hrEmployee->syncPermissions([]);
    }

    /**
     * Reverse: remove permissions from HR Manager and HR Employee; do not delete roles
     * as they might be assigned to users.
     */
    public function down(): void
    {
        foreach (['HR Manager', 'HR Employee'] as $name) {
            $role = Role::findByName($name, 'web');
            if ($role) {
                $role->syncPermissions([]);
            }
        }
    }
};
