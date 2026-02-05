<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Modules\Employee\Models\Department;
use App\Modules\Employee\Models\Designation;
use App\Modules\Employee\Models\Location;
use App\Modules\Employee\Models\Employee;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = ['Super Admin', 'HR Admin', 'Manager', 'Finance', 'Recruiter', 'Employee'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create Permissions
        $permissions = [
            'view employees', 'create employees', 'update employees', 'delete employees',
            'view attendance', 'create attendance', 'update attendance', 'delete attendance',
            'view leaves', 'create leaves', 'update leaves', 'delete leaves', 'approve leaves',
            'view payroll', 'create payroll', 'update payroll', 'delete payroll', 'run payroll',
            'manage tasks',
            'view performance', 'manage performance',
            'view expenses', 'approve expenses', 'process reimbursements', 'manage expense categories',
            'view training', 'manage training',
            'view shifts', 'manage shifts',
            'view assets', 'manage assets', 'approve asset returns', 'manage asset types',
            'view travel', 'approve travel',
            'view exit', 'manage exit',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdmin = Role::findByName('Super Admin');
        $superAdmin->givePermissionTo(Permission::all());

        $hrAdmin = Role::findByName('HR Admin');
        $hrAdmin->givePermissionTo([
            'view employees', 'create employees', 'update employees',
            'view attendance', 'view leaves', 'create leaves', 'update leaves', 'approve leaves',
            'manage tasks',
            'view performance', 'manage performance',
            'view expenses', 'approve expenses', 'manage expense categories',
            'view training', 'manage training',
            'view shifts', 'manage shifts',
            'view assets', 'manage assets', 'manage asset types',
            'view travel', 'approve travel',
            'view exit', 'manage exit',
        ]);
        // HR Admin does NOT get 'approve asset returns' – they can see/manage assets but not approve/decline return requests

        // Create Super Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@hrms.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $admin->assignRole('Super Admin');

        // Create HR Admin User
        $hrAdminUser = User::firstOrCreate(
            ['email' => 'hradmin@hrms.com'],
            [
                'name' => 'HR Admin',
                'password' => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $hrAdminUser->assignRole('HR Admin');

        // Create Departments
        $departments = [
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'Sales', 'code' => 'SALES'],
            ['name' => 'Marketing', 'code' => 'MKT'],
        ];

        foreach ($departments as $dept) {
            $dept['uuid'] = (string) Str::uuid();
            Department::firstOrCreate(
                ['code' => $dept['code']],
                array_merge($dept, ['is_active' => true])
            );
        }

        // Create Designations
        $designations = [
            ['name' => 'Manager', 'code' => 'MGR'],
            ['name' => 'Senior Developer', 'code' => 'SR_DEV'],
            ['name' => 'Developer', 'code' => 'DEV'],
            ['name' => 'HR Executive', 'code' => 'HR_EXEC'],
        ];

        foreach ($designations as $desig) {
            $desig['uuid'] = (string) Str::uuid();
            Designation::firstOrCreate(
                ['code' => $desig['code']],
                array_merge($desig, ['is_active' => true])
            );
        }

        // Create Locations
        $locations = [
            ['name' => 'Head Office', 'code' => 'HO', 'city' => 'Mumbai', 'country' => 'India'],
            ['name' => 'Branch Office', 'code' => 'BO', 'city' => 'Delhi', 'country' => 'India'],
        ];

        foreach ($locations as $loc) {
            $loc['uuid'] = (string) Str::uuid();
            Location::firstOrCreate(
                ['code' => $loc['code']],
                array_merge($loc, ['is_active' => true])
            );
        }

        // Seed sample data (10 employees with attendance, leaves, payroll)
        $this->call(SampleDataSeeder::class);
    }
}
