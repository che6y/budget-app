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
use Illuminate\Support\Facades\Route;

Auth::routes();
//Route::get('/account/settings', 'UserController@index');
//Route::put('/account/settings', 'UserController@update');
//Route::put('/account/token', 'ApiTokenController@update');

Route::get('/{any}', 'AppController@index')->where('any', '.*');
