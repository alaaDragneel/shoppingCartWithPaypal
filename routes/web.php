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

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/products', 'ProductsController@index');

Route::get('/card', 'ProductsController@getCard');

Route::get('/products/card/add/{product_id}', [
    'uses' => 'ProductsController@addToCard',
    'as' => 'add.to.card'
]);

Route::post('/checkOut', 'PayController@checkOut');

Route::get('/successCharge', 'PayController@successCharge');
Route::get('/errorCharge', 'PayController@errorCharge');
