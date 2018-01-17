<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Active
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($user = Auth::user()) {
			if (!$user->isActive()) {
				return redirect()->route('home');
			}
		}

		return $next($request);
	}
}
