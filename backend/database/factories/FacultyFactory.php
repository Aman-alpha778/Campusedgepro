<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacultyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'employee_id' => 'FAC'.strtoupper(substr($this->faker->unique()->uuid(), 0, 8)),
            'qualification' => $this->faker->randomElement(['M.Tech', 'Ph.D', 'MBA', 'M.Sc', 'M.Com']),
            'experience' => $this->faker->numberBetween(1, 20),
            'joining_date' => $this->faker->dateTimeBetween('-8 years', '-1 month'),
            'salary' => $this->faker->numberBetween(35000, 150000),
            'status' => 'active',
        ];
    }
}
