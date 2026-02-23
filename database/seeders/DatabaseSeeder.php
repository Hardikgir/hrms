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
        // Create Roles (including HR department: HR Admin, HR Manager, HR Employee)
        $roles = ['Super Admin', 'HR Admin', 'HR Manager', 'HR Employee', 'Manager', 'Finance', 'Recruiter', 'Employee'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
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
            'manage roles',
            'manage employment types', 'manage employment statuses',
            'manage departments', 'manage designations', 'manage locations',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdmin = Role::findByName('Super Admin');
        $superAdmin->givePermissionTo(Permission::all());

        // HR Admin – full HR access (no manage roles, no delete employees by default; no run payroll)
        $hrAdmin = Role::findByName('HR Admin');
        $hrAdmin->syncPermissions([
            'view employees', 'create employees', 'update employees', 'delete employees',
            'view attendance', 'create attendance', 'update attendance',
            'view leaves', 'create leaves', 'update leaves', 'approve leaves',
            'manage tasks',
            'view performance', 'manage performance',
            'view expenses', 'approve expenses', 'process reimbursements', 'manage expense categories',
            'view training', 'manage training',
            'view shifts', 'manage shifts',
            'view assets', 'manage assets', 'approve asset returns', 'manage asset types',
            'view travel', 'approve travel',
            'view exit', 'manage exit',
            'view payroll', 'create payroll', 'update payroll', 'run payroll',
            'manage employment types', 'manage employment statuses',
            'manage departments', 'manage designations', 'manage locations',
        ]);

        // HR Manager – view & approve; no settings, no create/update/delete employees, no run payroll
        $hrManager = Role::findByName('HR Manager');
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

        // HR Employee – ESS only (same as Employee; no admin panel access)
        $hrEmployee = Role::findByName('HR Employee');
        $hrEmployee->syncPermissions([]);

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

        // Create Designations (including HR department job titles)
        $designations = [
            ['name' => 'Manager', 'code' => 'MGR'],
            ['name' => 'Senior Developer', 'code' => 'SR_DEV'],
            ['name' => 'Developer', 'code' => 'DEV'],
            ['name' => 'HR Executive', 'code' => 'HR_EXEC'],
            ['name' => 'HR Admin', 'code' => 'HR_ADMIN'],
            ['name' => 'HR Manager', 'code' => 'HR_MGR'],
            ['name' => 'HR Employee', 'code' => 'HR_EMP'],
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
