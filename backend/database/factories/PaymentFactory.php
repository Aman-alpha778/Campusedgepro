<?php

namespace Database\Factories;

use App\Models\Fee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fee_id' => Fee::factory(),
            'amount' => $this->faker->numberBetween(1000, 50000),
            'payment_method' => $this->faker->randomElement(['UPI', 'Card', 'Bank Transfer', 'Cash']),
            'transaction_id' => 'TXN'.strtoupper(str_replace('-', '', substr($this->faker->unique()->uuid(), 0, 16))),
            'payment_date' => $this->faker->dateTimeBetween('-12 months', 'now'),
        ];
    }
}
