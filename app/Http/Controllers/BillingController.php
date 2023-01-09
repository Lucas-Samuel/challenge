<?php

namespace App\Http\Controllers;

use App\Http\Services\DebtService;
use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
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
