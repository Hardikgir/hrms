<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Modules\Employee\Models\Employee;
use App\Modules\Employee\Models\Department;
use App\Modules\Employee\Models\Designation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'HR Admin']);
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Admin');
    }

    public function test_employee_index_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/employees');

        $response->assertStatus(200);
    }

    public function test_employee_can_be_created(): void
    {
        $department = Department::factory()->create();
        $designation = Designation::factory()->create();

        $response = $this->actingAs($this->admin)->post('/employees', [
            'employee_id' => 'EMP001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'joining_date' => '2024-01-01',
            'employment_type' => 'full_time',
        ]);

        $response->assertRedirect('/employees');
        $this->assertDatabaseHas('employees', [
            'employee_id' => 'EMP001',
            'email' => 'john.doe@example.com',
        ]);
    }

    public function test_employee_can_be_updated(): void
    {
        $employee = Employee::factory()->create();
        $department = Department::factory()->create();

        $response = $this->actingAs($this->admin)->put("/employees/{$employee->id}", [
            'employee_id' => $employee->employee_id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => $employee->email,
            'department_id' => $department->id,
            'designation_id' => $employee->designation_id,
            'joining_date' => $employee->joining_date->format('Y-m-d'),
            'employment_type' => $employee->employment_type,
        ]);

        $response->assertRedirect('/employees');
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => 'Jane',
        ]);
    }

    public function test_employee_can_be_deleted(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/employees/{$employee->id}");

        $response->assertRedirect('/employees');
        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
    }
}

