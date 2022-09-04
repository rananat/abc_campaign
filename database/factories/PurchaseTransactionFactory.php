<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseTransaction>
 */
class PurchaseTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'total_spent' => $this->faker->numberBetween(100, 1000),
            'total_saving' => $this->faker->numberBetween(100, 1000),
            'transaction_at' => $this->faker->dateTimeBetween('-1 month')
            //'transaction_at' => $this->faker->dateTimeBetween('-2 year', '-1 year')
        ];
    }
}
