<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use App\Modules\Employee\Models\Designation;

return new class extends Migration
{
    /**
     * Seed HR department designations (job titles): HR Admin, HR Manager, HR Employee.
     */
    public function up(): void
    {
        $designations = [
            ['name' => 'HR Admin', 'code' => 'HR_ADMIN'],
            ['name' => 'HR Manager', 'code' => 'HR_MGR'],
            ['name' => 'HR Employee', 'code' => 'HR_EMP'],
        ];
        foreach ($designations as $d) {
            Designation::firstOrCreate(
                ['code' => $d['code']],
                array_merge($d, ['uuid' => (string) Str::uuid(), 'is_active' => true])
            );
        }
    }

    public function down(): void
    {
        Designation::whereIn('code', ['HR_ADMIN', 'HR_MGR', 'HR_EMP'])->delete();
    }
};
