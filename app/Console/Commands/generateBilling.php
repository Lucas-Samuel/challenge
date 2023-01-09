<?php

namespace App\Console\Commands;

use App\Http\Services\DebtService;
use App\Models\Billing;
use Illuminate\Console\Command;

use function Psy\debug;

class generateBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all pending billings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(DebtService $debtService)
    {
        $debts = $debtService->getPending();

        foreach ($debts as $debt) {
            // $boleto = $this->generateBoleto($debt);

            Billing::create([
                'debt_id'       => $debt->id,
                'bar_code'      => '123', // $boleto['codigo_de_barras']
                'our_number'    => '123', // $boleto['nosso_numero']
                'due_date'      => $debt->debt_due_date,
                'status'        => Billing::AWAITING
            ]);

            // $this->sendEmail($debt->email, 'Kanastra - Boleto de cobrança', $boleto['pdf']);
        }

        return Command::SUCCESS;
    }
}
