<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\DebtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/debts', [DebtController::class, 'index']);
// Route::get('/debts/{id}', [DebtController::class, 'show']);
// Route::put('/debts/{id}', [DebtController::class, 'update']);
// Route::post('/debts', [DebtController::class, 'store']);
// Route::delete('/debts/{id}', [DebtController::class, 'destroy']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::apiResource('debts', DebtController::class);

Route::post('/billings/generate', [BillingController::class, 'generate']);
Route::post('/billings/notify', [BillingController::class, 'notify']);
