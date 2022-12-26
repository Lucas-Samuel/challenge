<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Debt::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'debts' => 'required|mimes:csv,txt',
        ]);

        $debts = $this->csvToArray($validated['debts'], $request->get('delimiter'));

        try {
            DB::beginTransaction();

            foreach ($debts as $debt) {
                Debt::create($debt);
            }

            DB::commit();
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollBack();

            return response()->json([
                'status'    => 'error',
                'message'   => 'Error registering debt',
                'error'     => $exception->errorInfo
            ], 400);
        }

        return response()->json([
            'status'    => 'success',
            'message'   => 'Successfully registered debts'
        ], 201);
    }

    function csvToArray($filename, $delimiter = null)
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  \App\Models\Debt  $debt
     * @return \Illuminate\Http\Response
     */
    public function show(Debt $debt)
    {
        return $debt;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Debt  $debt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Debt $debt)
    {
        if (!$debt->update($request->all())) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Error updating debt'
            ], 400);
        }

        return $debt;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Debt  $debt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Debt $debt)
    {
        $debt->delete();

        return response()->json([
            'status'    => 'error',
            'message'   => 'Successfully removed debt'
        ], 200);
    }

}
