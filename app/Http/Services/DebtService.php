<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

class DebtService
{

    public function getPending()
    {
        return DB::table('debts')
            ->select('debts.*')
            ->leftJoin('billings', 'debts.id', '=', 'billings.debt_id')
            ->whereNull('billings.id')
            ->whereNull('debts.deleted_at')
            ->where('debts.debt_due_date', '>', date('Y-m-d'))
            ->get();
    }

}
