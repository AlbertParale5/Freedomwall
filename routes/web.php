<?php

use Illuminate\Support\Facades\Route;
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

    return view('home', [
        'Fwall' => App\Fwall::latest()->get()
    ]);
});
Route::post('/store', 'FwallController@store');
Route::get('/load_data', 'FwallController@load_data');
Route::get('edit/{id}', 'FwallController@edit');
Route::post(' delete/{id}', 'FwallController@destroy');
Route::post('update/{id}', 'FwallController@update');