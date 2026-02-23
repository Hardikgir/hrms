<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupJobDetailsPermissions extends Command
{
    protected $signature = 'setup:job-details-permissions';
    protected $description = 'Setup permissions for job details dropdowns and assign to roles';

    public function handle()
    {
        $this->info('Setting up job details permissions...');

        // Create permissions
        $permissions = [
            'manage departments',
            'manage designations',
            'manage locations',
            'manage employment types',
            'manage employment statuses',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
            $this->info("✓ Permission '{$permissionName}' created/verified");
        }

        // Assign to Super Admin (gets all permissions automatically, but ensure they exist)
        $superAdmin = Role::findByName('Super Admin');
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
            $this->info('✓ Permissions assigned to Super Admin');
        }

        // Assign to HR Admin
        $hrAdmin = Role::findByName('HR Admin');
        if ($hrAdmin) {
            $hrAdmin->givePermissionTo($permissions);
            $this->info('✓ Permissions assigned to HR Admin');
        }

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->info('✓ Permission cache cleared');

        $this->info('Job details permissions setup complete!');
        return 0;
    }
}
