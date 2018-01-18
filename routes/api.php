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

Route::get('/pool/stats', 'Api\StatsController@index')->name('api.stats');
Route::get('/pool/stats/detailed', 'Api\StatsController@detailed')->name('api.stats.detailed');

// this API route is in web.php to use the same session as web interface
// Route::post('/miners', 'Api\MinersController@list')->name('api.miners');
