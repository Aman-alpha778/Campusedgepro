<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CampusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company().' Campus '.strtoupper($this->faker->bothify('##')),
            'code' => 'CMP'.strtoupper(substr($this->faker->unique()->uuid(), 0, 8)),
            'email' => 'campus.'.strtolower(substr($this->faker->unique()->uuid(), 0, 8)).'@campusedge.test',
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => 'India',
            'status' => $this->faker->randomElement(['active', 'active', 'inactive']),
        ];
    }
}
