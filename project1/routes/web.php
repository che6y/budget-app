<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/purchases', 'PurchaseController@index');
Route::post('/purchases', 'PurchaseController@store');
Route::get('/purchases/create', 'PurchaseController@create');
Route::get('/purchases/{id}/edit', 'PurchaseController@edit');
Route::put('/purchases/{id}', 'PurchaseController@update');
Route::delete('/purchases/{id}', 'PurchaseController@destroy');
