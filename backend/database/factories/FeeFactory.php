<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'fee_type' => $this->faker->randomElement(['Tuition Fee', 'Library Fee', 'Hostel Fee', 'Exam Fee', 'Transport Fee']),
            'amount' => $this->faker->numberBetween(5000, 90000),
            'due_date' => $this->faker->dateTimeBetween('-2 months', '+4 months'),
            'status' => $this->faker->randomElement(['pending', 'partial', 'paid']),
        ];
    }
}
