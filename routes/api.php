<?php

use Illuminate\Http\Request;
use App\Purchase;
use App\Http\Resources\PurchaseCollection;

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

//Route::get('/purchases', function () {
//    return new PurchaseCollection(Purchase::all());
//});
Route::get('/purchases', 'PurchasesController@index');
Route::get('/purchases/{purchase}', 'PurchasesController@show');
Route::put('/purchases/{purchase}', 'PurchasesController@update');
Route::post('/purchases/', 'PurchasesController@store');
Route::delete('/purchases/{purchase}', 'PurchasesController@destroy');