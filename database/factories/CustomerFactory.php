<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customers>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $createdAt = $this->faker->dateTimeBetween('-2 year', '-1 year');
        $updatedAt = $this->faker->dateTimeBetween($createdAt);
        return [
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['M','F']),
            'date_of_birth' => $this->faker->date(),
            'contact_number' => $this->faker->numerify('##-########'),
            'email' => $this->faker->email(),
            'created_at'=>$createdAt,
		    'updated_at'=>$updatedAt
        ];
    }
}
