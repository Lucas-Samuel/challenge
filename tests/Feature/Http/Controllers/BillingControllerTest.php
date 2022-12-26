<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Billing;
use App\Models\Debt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BillingControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_generate()
    {
        Debt::create([
            'name' => fake()->name(),
            'government_id' => fake()->numerify('###########'),
            'email' => fake()->email(),
            'debt_amount' => fake()->randomFloat(2, 0, 10000),
            'debt_due_date' => fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
            'debt_id' => fake()->randomNumber()
        ]);

        $response = $this->post('/api/billings/generate');
        $response->assertStatus(200);
    }

    public function test_notify()
    {
        $debt = Debt::query()->inRandomOrder()->first();

        $response = $this->json('POST', '/api/billings/notify', [
            'debtId'        => $debt->id,
            'paidAt'        => fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
            'paidAmount'    => $debt->debt_amount,
            'paidBy'        => $debt->name,
        ]);

        $response->assertStatus(200);
    }

    public function test_notify_already_paid()
    {
        $billing = Billing::where('status', Billing::PAID)->first();

        $response = $this->json('POST', '/api/billings/notify', [
            'debtId'        => $billing->debt_id,
            'paidAt'        => $billing->paid_at,
            'paidAmount'    => $billing->paid_amount,
            'paidBy'        => $billing->paid_by,
        ]);

        $response->assertStatus(200);
    }
}
