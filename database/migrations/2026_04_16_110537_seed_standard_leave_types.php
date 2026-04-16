<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $types = [
            ['name' => 'Annual Leave', 'max_days' => 30, 'is_paid' => true],
            ['name' => 'Unpaid Leave', 'max_days' => 0, 'is_paid' => false],
            ['name' => 'Sick Leave', 'max_days' => 15, 'is_paid' => true],
            ['name' => 'Hajj Leave', 'max_days' => 10, 'is_paid' => true],
            ['name' => 'Marriage Leave', 'max_days' => 5, 'is_paid' => true],
            ['name' => 'Paternity Leave', 'max_days' => 3, 'is_paid' => true],
            ['name' => 'Death / Compassionate', 'max_days' => 5, 'is_paid' => true],
            ['name' => 'Maternity Leave', 'max_days' => 60, 'is_paid' => true],
            ['name' => 'Official / Training Leave', 'max_days' => 0, 'is_paid' => true],
            ['name' => 'Others', 'max_days' => 0, 'is_paid' => true],
        ];

        foreach ($types as $type) {
            \App\Modules\Leave\Models\LeaveType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'code' => strtoupper(str_replace(' ', '_', $type['name'])),
                    'max_days_per_year' => $type['max_days'],
                    'is_paid' => $type['is_paid'],
                    'requires_approval' => true,
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not really reversible as it seeds data, but we could delete them if needed.
    }
};
