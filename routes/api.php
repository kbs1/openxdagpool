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

Route::get('/pool/stats/detailed', 'Api\StatsController@detailed')->name('api.stats.detailed');
Route::post('/wallet/balance', 'Api\BalancesController@check')->name('api.balances');

// also see API routes in web.php (for routes that use the same session as web does)
