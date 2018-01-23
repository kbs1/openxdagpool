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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/pages/{page}', 'PagesController@index')->name('pages')->where('page', '(.+)');

Route::get('/stats', 'StatsController@index')->name('stats');

Route::get('/user/miners', 'User\MinersController@index')->name('miners');
Route::post('/user/miners', 'User\MinersController@create')->name('miners.create');
Route::delete('/user/miners', 'User\MinersController@delete')->name('miners.delete');
Route::post('/user/miners/alerts', 'User\MinersController@alerts')->name('miners.alerts');
Route::get('/user/miners/{address}/payments', 'User\PaymentsController@miner')->name('miners.payments');
Route::get('/user/miners/{address}/payments/export', 'User\PaymentsController@exportMiner')->name('miners.payments.export');

Route::get('/user/profile', 'User\ProfileController@index')->name('profile');
Route::post('/user/profile', 'User\ProfileController@update')->name('profile.update');
Route::get('/user/payments', 'User\PaymentsController@user')->name('user.payments');
Route::get('/user/payments/export', 'User\PaymentsController@exportUser')->name('user.payments.export');

// API calls, uses the same authentication as web interface
Route::post('/api/miners', 'Api\MinersController@list')->name('api.miners');
Route::get('/api/pool/stats', 'Api\StatsController@index')->name('api.stats');
