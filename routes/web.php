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
Route::get('/leaderboard', 'LeaderboardController@index')->name('leaderboard');
Route::get('/pages/{page}', 'PagesController@index')->name('pages')->where('page', '(.+)');

Route::get('/stats', 'StatsController@index')->name('stats');

Route::get('/user/miners', 'User\MinersController@index')->name('miners');
Route::post('/user/miners', 'User\MinersController@create')->name('miners.create');
Route::put('/user/miners', 'User\MinersController@update')->name('miners.update');
Route::delete('/user/miners', 'User\MinersController@delete')->name('miners.delete');
Route::post('/user/miners/alerts', 'User\MinersController@alerts')->name('miners.alerts');
Route::get('/user/miners/{uuid}/payouts-graph', 'User\PayoutsController@minerPayoutsGraph')->name('miners.payouts.graph');
Route::get('/user/miners/{uuid}/payouts-listing', 'User\PayoutsController@minerPayoutsListing')->name('miners.payouts.listing');
Route::get('/user/miners/{uuid}/payouts-graph/export', 'User\PayoutsController@exportMinerPayoutsGraph')->name('miners.payouts.export-graph');
Route::get('/user/miners/{uuid}/payouts-listing/export', 'User\PayoutsController@exportMinerPayoutsListing')->name('miners.payouts.export-listing');
Route::get('/user/miners/{uuid}/hashrate-graph/{type}', 'User\HashrateController@minerGraph')->name('miners.hashrate.graph');

Route::get('/user/profile', 'User\ProfileController@index')->name('profile');
Route::post('/user/profile', 'User\ProfileController@update')->name('profile.update');
Route::get('/user/payouts-graph', 'User\PayoutsController@userPayoutsGraph')->name('user.payouts.graph');
Route::get('/user/payouts-listging', 'User\PayoutsController@userPayoutsListing')->name('user.payouts.listing');
Route::get('/user/payouts-graph/export', 'User\PayoutsController@exportUserPayoutsGraph')->name('user.payouts.export-graph');
Route::get('/user/payouts-listing/export', 'User\PayoutsController@exportUserPayoutsListing')->name('user.payouts.export-listing');
Route::get('/user/hashrate-graph/{type}', 'User\HashrateController@userGraph')->name('user.hashrate.graph');

Route::get('/user/admin/users', 'User\AdministrationController@users')->name('user.admin.users');
Route::get('/user/admin/users/{id}', 'User\AdministrationController@editUser')->name('user.admin.edit-user');
Route::post('/user/admin/users/{id}', 'User\AdministrationController@updateUser')->name('user.admin.update-user');
Route::get('/user/admin/settings', 'User\AdministrationController@poolSettings')->name('user.admin.settings');
Route::post('/user/admin/settings', 'User\AdministrationController@savePoolSettings')->name('user.admin.settings.save');
Route::get('/user/admin/mass-email', 'User\AdministrationController@massEmail')->name('user.admin.mass-email');
Route::post('/user/admin/mass-email', 'User\AdministrationController@sendMassEmail')->name('user.admin.mass-email.send');
Route::get('/user/admin/miners-by-ip', 'User\AdministrationController@minersByIp')->name('user.admin.miners-by-ip');
Route::get('/user/admin/miners-by-hashrate', 'User\AdministrationController@minersByHashrate')->name('user.admin.miners-by-hashrate');
Route::get('/user/admin/pool-state', 'User\AdministrationController@poolState')->name('user.admin.pool-state');

Route::get('/payouts-graph', 'PayoutsController@addressPayoutsGraph')->name('payouts.graph');
Route::get('/payouts-listing', 'PayoutsController@addressPayoutsListing')->name('payouts.listing');
Route::get('/payouts-graph/export', 'PayoutsController@exportAddressPayoutsGraph')->name('payouts.export-graph');
Route::get('/payouts-listing/export', 'PayoutsController@exportAddressPayoutsListing')->name('payouts.export-listing');

// API calls, uses the same authentication as web interface
Route::post('/api/miners', 'Api\MinersController@list')->name('api.miners');
Route::get('/api/pool/stats', 'Api\StatsController@index')->name('api.stats');
