<?php

namespace App\Http\Services;

use App\Models\Debt;
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

    public function getDebtsFromCsv($filename, $delimiter = null)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $handle = fopen($filename, 'r');
        if ($handle === false) {
            return false;
        }

        if (!$delimiter) {
            $delimiters = [';' => 0, ',' => 0, "\t" => 0, "|" => 0];
            $firstLine = fgets($handle);
            rewind($handle);

            foreach ($delimiters as $delimiter => &$count) {
                $count = count(str_getcsv($firstLine, $delimiter));
            }

            $delimiter = array_search(max($delimiters), $delimiters);
        }

        $data = [];
        $header = null;
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if (!$header) {
                $header = $row;
            } else {
                $data[] = array_combine($header, $row);
            }
        }

        fclose($handle);
        unlink($filename);

        return $data;
    }

    public function save($debts)
    {
        try {
            DB::beginTransaction();

            foreach ($debts as $debt) {
                Debt::create($debt);
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
