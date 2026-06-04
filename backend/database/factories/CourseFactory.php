<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'name' => $this->faker->unique()->words(3, true),
            'code' => 'CRS'.strtoupper(substr($this->faker->unique()->uuid(), 0, 8)),
            'duration' => $this->faker->randomElement(['2 Years', '3 Years', '4 Years']),
            'semester_count' => $this->faker->randomElement([4, 6, 8]),
            'total_semesters' => $this->faker->randomElement([4, 6, 8]),
            'status' => 'active',
        ];
    }
}
