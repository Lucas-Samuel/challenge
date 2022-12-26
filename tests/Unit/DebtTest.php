<?php

namespace Tests\Unit;

use App\Http\Services\DebtService;
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
        $pendignDebts = (new DebtService)->getPending();

        $this->assertNotEmpty($pendignDebts);
    }

    public function test_not_has_pending_debts()
    {
        $pendignDebts = (new DebtService)->getPending();

        $this->assertEmpty($pendignDebts);
    }
}
