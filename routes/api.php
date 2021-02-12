<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Orders endpoints
Route::middleware('auth:api')->group(function ()
{
    Route::get('orders','OrderController@index');
    Route::post('orders','OrderController@create');
    Route::put('orders/{id}','OrderController@update');
    Route::get('orders/{id}','OrderController@view');
    Route::post('orders/{id}/add','OrderController@add');
    Route::post('orders/{id}/pay','OrderController@pay');
});