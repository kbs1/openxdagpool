<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class HomeController extends Controller
{
	public function index()
	{
		$message = env('IMPORTANT_MESSAGE');

		try {
			$until = env('IMPORTANT_MESSAGE_UNTIL') ? new Carbon(env('IMPORTANT_MESSAGE_UNTIL')) : null;
		} catch (\Exception $ex) {
			$until = Carbon::now();
		}


		if ($until && $until <= Carbon::now())
			$message = null;

		return view('home', [
			'message' => $message,
		]);
	}
}
