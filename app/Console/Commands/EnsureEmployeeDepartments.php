<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Modules\Employee\Models\Department;
use App\Modules\Employee\Models\Employee;

class EnsureEmployeeDepartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:ensure-employee-departments {--assign-missing : Assign a default department to employees with NULL department_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure departments exist and optionally assign missing employee department_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Ensuring departments exist...');

        $defaults = [
            ['name' => 'General', 'code' => 'GEN'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'Sales', 'code' => 'SALES'],
            ['name' => 'Marketing', 'code' => 'MKT'],
        ];

        $created = 0;
        foreach ($defaults as $d) {
            $dept = Department::firstOrCreate(
                ['code' => $d['code']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $d['name'],
                    'is_active' => true,
                ]
            );
            if ($dept->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->info("✓ Departments ensured. Newly created: {$created}. Total: " . Department::count());

        if (! $this->option('assign-missing')) {
            $this->info('Skipped assigning missing employee departments (use --assign-missing).');
            return 0;
        }

        $general = Department::where('code', 'GEN')->first();
        if (! $general) {
            $this->error('General department (GEN) not found; cannot assign.');
            return 1;
        }

        $missingCount = Employee::whereNull('department_id')->count();
        if ($missingCount === 0) {
            $this->info('✓ No employees with NULL department_id.');
            return 0;
        }

        $updated = Employee::whereNull('department_id')->update(['department_id' => $general->id]);
        $this->info("✓ Assigned department GEN to {$updated} employee(s) that were missing department_id.");

        return 0;
    }
}
