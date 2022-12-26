<?php

namespace App\Http\Controllers;

use App\Http\Services\DebtService;
use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{

    // cron 0 8 * * * curl -i -X POST -H "Content-Type: application/json" http://localhost/api/billing/generate
    public function generate()
    {
        $debts = (new DebtService)->getPending();

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

        return response()->json([
            'status'    => 'success',
            'message'   => 'Billings generated successfully'
        ], 200);
    }

    public function notify(Request $request)
    {
        $validated = $request->validate([
            'debtId'        => 'required|integer',
            'paidAt'        => 'required',
            'paidAmount'    => 'required|between:0,9999.99',
            'paidBy'        => 'required|string',
        ]);

        $billing = Billing::where('debt_id', $validated['debtId'])->first();

        if (!$billing) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Billing not found'
            ], 400);
        }

        if ($billing->status == Billing::PAID) {
            return response()->json([
                'status'    => 'success',
                'message'   => 'Billing has already been paid'
            ], 200);
        }

        $billing->update([
            'status'        => Billing::PAID,
            'paid_at'       => $validated['paidAt'],
            'paid_amount'   => $validated['paidAmount'],
            'paid_by'       => $validated['paidBy'],
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Billing updated successfully'
        ], 200);
    }
}
