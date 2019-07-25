<?php

use Illuminate\Http\Request;

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

// Purchases
Route::get('/purchases', 'PurchaseController@index');
Route::get('/purchases/{purchase}', 'PurchaseController@show');
Route::put('/purchases/{purchase}', 'PurchaseController@update');
Route::post('/purchases/', 'PurchaseController@store');
Route::delete('/purchases/{purchase}', 'PurchaseController@destroy');

// Category
Route::get('/categories', 'CategoryController@index');
Route::get('/categories/{category}', 'CategoryController@show');
Route::put('/categories/{category}', 'CategoryController@update');
Route::post('/categories/', 'CategoryController@store');
Route::delete('/categories/{category}', 'CategoryController@destroy');

Route::get('/pie-data', 'AppController@getData');

//Route::get('/options', 'OptionController@index');
//Route::get('/options/find', 'OptionController@find');
//Route::put('/options/{option}', 'OptionController@update');
