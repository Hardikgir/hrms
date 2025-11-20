<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Employee\Models\Designation>
 */
class DesignationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => $this->faker->randomElement(['Manager', 'Senior Developer', 'Developer', 'HR Executive', 'Accountant', 'Sales Executive']),
            'code' => strtoupper($this->faker->unique()->lexify('???')),
            'description' => $this->faker->sentence(),
            'min_salary' => $this->faker->numberBetween(300000, 500000),
            'max_salary' => $this->faker->numberBetween(800000, 1500000),
            'is_active' => true,
        ];
    }
}
