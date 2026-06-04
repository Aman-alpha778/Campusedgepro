<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true).' Department';

        return [
            'name' => $name,
            'code' => 'DEP'.strtoupper(substr($this->faker->unique()->uuid(), 0, 8)),
            'slug' => Str::slug($name.'-'.$this->faker->unique()->numberBetween(1000, 9999)),
            'description' => $this->faker->sentence(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'location' => $this->faker->randomElement(['Academic Block A', 'Main Building', 'Innovation Wing']),
            'established_year' => $this->faker->numberBetween(1998, 2022),
            'total_intake' => $this->faker->randomElement([60, 120, 180, 240]),
            'status' => 'active',
        ];
    }
}
