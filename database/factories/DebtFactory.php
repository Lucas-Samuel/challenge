<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Debt>
 */
class DebtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'government_id' => fake()->numerify('###########'),
            'email' => fake()->email(),
            'debt_amount' => fake()->randomFloat(2, 0, 10000),
            'debt_due_date' => fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
            'debt_id' => fake()->randomNumber()
        ];
    }
}
