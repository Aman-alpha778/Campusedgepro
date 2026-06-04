<?php

namespace Database\Factories;

use App\Models\Campus;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'campus_id' => Campus::factory(),
            'department_id' => Department::factory(),
            'course_id' => Course::factory(),
            'roll_number' => 'ROLL'.strtoupper(substr($this->faker->unique()->uuid(), 0, 8)),
            'registration_number' => 'REG'.strtoupper(substr($this->faker->unique()->uuid(), 0, 8)),
            'dob' => $this->faker->dateTimeBetween('-24 years', '-17 years'),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'address' => $this->faker->address(),
            'guardian_name' => $this->faker->name(),
            'guardian_phone' => $this->faker->phoneNumber(),
            'admission_date' => $this->faker->dateTimeBetween('-18 months', 'now'),
            'status' => 'active',
        ];
    }
}
