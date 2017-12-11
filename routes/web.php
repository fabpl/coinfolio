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

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/markets/{market}', 'MarketsController@show')->name('markets.show');

Route::post('/profil/markets', 'Users\MarketsController@store')->name('profil.markets.store');
Route::delete('/profil/markets/{market}', 'Users\MarketsController@delete')->name('profil.markets.delete');
Route::post('/profil/markets/{market}/orders', 'Users\OrdersController@store')->name('profil.markets.orders.store');
Route::delete('/profil/markets/{market}/orders/{order}', 'Users\OrdersController@delete')->name('profil.markets.orders.delete');
