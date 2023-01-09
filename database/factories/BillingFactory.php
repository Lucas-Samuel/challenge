<?php

namespace Database\Factories;

use App\Models\Billing;
use App\Models\Debt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Billing>
 */
class BillingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'debt_id'       => Debt::factory(),
            'bar_code'      => fake()->randomNumber(),
            'our_number'    => fake()->randomNumber(),
            'due_date'      => fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
            'status'        => Billing::AWAITING
        ];
    }
}
