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
        $response = $this->post('/api/billings/generate');
        $response->assertStatus(200);
    }

    public function test_notify()
    {
        $debt = Debt::inRandomOrder()->first();

        if (!$debt) {
            return true;
        }

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

        if (!$billing) {
            return true;
        }

        $response = $this->json('POST', '/api/billings/notify', [
            'debtId'        => $billing->debt_id,
            'paidAt'        => $billing->paid_at,
            'paidAmount'    => $billing->paid_amount,
            'paidBy'        => $billing->paid_by,
        ]);

        $response->assertStatus(200);
    }
}
