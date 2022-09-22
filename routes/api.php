<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\PayersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;
use App\Models\Merchant;
use App\Models\Payment;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::resource('payments', PaymentController::class);
// Route::apiResource('payers', PayersController::class);

Route::get('/v1/payers', [PayersController::class, 'index']);
Route::post('/v1/payers', [PayersController::class, 'store']);
Route::get('/v1/payer/{id}', [PayersController::class, 'show']);

// PAYMENTS
Route::get('/v1/payments', [PaymentController::class, 'index']);
Route::post('/v1/payments', [PaymentController::class, 'store']);
Route::get('/v1/payments/history', [PaymentController::class, 'getHistory']);

// CARD
Route::get('/v1/card', [CardController::class, 'index']);
Route::post('/v1/card', [CardController::class, 'store']);
Route::get('/v1/card/{id}', [CardController::class, 'show']);

// TRANSACTION
Route::post('/v1/transaction/collect', [TransactionController::class, 'makeCollection']);
Route::post('/v1/transaction/status/{id}', [TransactionController::class, 'getStatus']);
Route::post('/v1/transaction/info', [TransactionController::class, 'getInfo']);
Route::post('/v1/transaction/balance', [TransactionController::class, 'getBalance']);
Route::get('/v1/transaction/{id}', [TransactionController::class, 'show']);

// MERCHANT
Route::get('/v1/merchant', [MerchantController::class, 'index']);
Route::post('/v1/merchant', [MerchantController::class, 'store']);