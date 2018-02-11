<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Setting;

class HomeController extends Controller
{
	public function index()
	{
		$message = Setting::get('important_message_html');

		try {
			$until = Setting::get('important_message_until') ? new Carbon(Setting::get('important_message_until')) : null;
			if ($until)
				$until->hour(23)->minute(59)->second(59);
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
