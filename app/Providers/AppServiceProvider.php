<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use Carbon\Carbon;
use Setting;

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
			$view->with('contactEmail', Setting::get('contact_email'));
			$view->with('headerBackgroundColor', Setting::get('header_background_color', '#00D1B2'));
		});
	}
}
