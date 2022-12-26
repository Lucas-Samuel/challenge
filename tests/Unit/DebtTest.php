<?php

namespace Tests\Unit;

use App\Http\Services\DebtService;
use App\Models\Debt;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DebtTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_pending_debts()
    {
        Debt::create([
            'name' => fake()->name(),
            'government_id' => fake()->numerify('###########'),
            'email' => fake()->email(),
            'debt_amount' => fake()->randomFloat(2, 0, 10000),
            'debt_due_date' => fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
            'debt_id' => fake()->randomNumber()
        ]);

        $pendignDebts = (new DebtService)->getPending();

        $this->assertNotEmpty($pendignDebts);
    }

    public function test_not_has_pending_debts()
    {
        $response = $this->post('/api/billings/generate');
        $response->assertStatus(200);

        $pendignDebts = (new DebtService)->getPending();

        $this->assertEmpty($pendignDebts);
    }

    public function test_get_debts_from_csv()
    {
        $header = 'name,government_id,email,debt_amount,debt_due_date,debt_id';
        $row1 = implode(',', [fake()->name(), fake()->numerify('###########'), fake()->email(), fake()->randomFloat(2, 0, 10000), fake()->dateTimeBetween('now', '+5 days')->format('Y-m-d'), fake()->randomNumber()]);

        $content = implode("\n", [$header, $row1]);
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);

        $pendignDebts = (new DebtService)->getDebtsFromCsv(stream_get_meta_data($file->tempFile)['uri']);

        $this->assertNotEmpty($pendignDebts);
    }
}
