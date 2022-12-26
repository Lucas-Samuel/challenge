<?php

namespace App\Http\Controllers;

use App\Http\Services\DebtService;
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

        $debts = (new DebtService)->getDebtsFromCsv($validated['debts'], $request->get('delimiter'));

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
