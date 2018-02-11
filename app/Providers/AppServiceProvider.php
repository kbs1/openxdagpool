<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		view()->composer('*', function($view) {
			if ($user = Auth::user()) {
				$user->last_seen_at = Carbon::now();
				$user->save();
			}

			$view->with('authUser', $user);
		});
	}
}
