<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Billing;
use App\Models\Debt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_generate()
    {
        Debt::factory()->create();

        $response = $this->artisan('billing:generate');
        $response->assertOk();
    }

    public function test_notify_paid()
    {
        $billing = Billing::factory()->create();
        $debt = Debt::where('id', $billing->debt_id)->first();

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
        $billing = Billing::factory()->create();
        $debt = Debt::where('id', $billing->debt_id)->first();

        $postData = [
            'debtId'        => $debt->id,
            'paidAt'        => fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
            'paidAmount'    => $debt->debt_amount,
            'paidBy'        => $debt->name,
        ];

        $response = $this->json('POST', '/api/billings/notify', $postData);
        $response->assertStatus(200);

        $secondResponse = $this->json('POST', '/api/billings/notify', $postData);
        $secondResponse->assertStatus(200);
    }
}
