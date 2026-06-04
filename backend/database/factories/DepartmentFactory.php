<?php

namespace Database\Factories;

use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'campus_id' => Campus::factory(),
            'name' => $this->faker->unique()->words(2, true).' Department',
            'code' => 'DEP'.strtoupper(substr($this->faker->unique()->uuid(), 0, 8)),
            'status' => 'active',
        ];
    }
}
