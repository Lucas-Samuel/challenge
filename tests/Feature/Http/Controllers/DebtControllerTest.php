<?php

namespace Tests\Feature;

use App\Models\Debt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DebtControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/api/debts');
        $response->assertStatus(200);
    }

    public function test_store()
    {
        $header = 'name,government_id,email,debt_amount,debt_due_date,debt_id';
        $row1 = implode(',', [fake()->name(), fake()->numerify('###########'), fake()->email(), fake()->randomFloat(2, 0, 10000), fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'), fake()->randomNumber()]);

        $content = implode("\n", [$header, $row1]);

        $response = $this->json('POST', '/api/debts', [
            'debts' => UploadedFile::fake()->createWithContent('test.csv', $content)
        ]);

        $response->assertStatus(201);
    }

    public function test_show()
    {
        $debt = Debt::query()->inRandomOrder()->first();

        $response = $this->get("/api/debts/{$debt->id}");
        $response->assertStatus(200);
    }

    public function test_update()
    {
        $debt = Debt::query()->inRandomOrder()->first();
        $debt->name = fake()->name();
        $debt->debt_amount = fake()->randomFloat(2, 0, 10000);

        $response = $this->put("/api/debts/{$debt->id}", $debt->toArray());
        $response->assertStatus(200);
    }

    // public function test_destroy()
    // {
    //     $debt = Debt::query()->inRandomOrder()->first();

    //     $response = $this->delete("/api/debts/{$debt->id}");
    //     $response->assertStatus(200);
    // }
}
