<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Orders endpoints
Route::get('/orders',[OrderController::class, 'index']);
Route::post('orders',[OrderController::class, 'create']);
Route::put('orders/{id}',[OrderController::class, 'update']);
Route::get('orders/{id}',[OrderController::class, 'view']);
Route::delete('orders/{id}',[OrderController::class, 'delete']);
Route::post('orders/{id}/add',[OrderController::class, 'add']);
Route::post('orders/{id}/pay',[OrderController::class, 'pay']);

//Payment provider endpoints
Route::get('payment-providers',[PaymentProvider::class, 'index']);
Route::get('payment-providers/{id}',[PaymentProvider::class, 'view']);