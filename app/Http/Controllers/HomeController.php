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

		$other_pools = Setting::get('other_pools', null);
		$pools = [];
		$current_pool_name = null;

		if (strlen($other_pools) > 1) {
			$other_pools = explode(';', $other_pools);
			if (count($other_pools) > 1) {
				foreach ($other_pools as $pool) {
					$pool = explode('|', $pool);

					if (count($pool) !== 2)
						continue;

					$pool[0] = trim($pool[0]);
					$pool[1] = trim($pool[1]);

					if ($pool[0] === '' || $pool[0] === '*' || $pool[1] === '')
						continue;

					if (substr($pool[0], -1) === '*') {
						$is_current_pool = true;
						$pool[0] = $current_pool_name = substr($pool[0], 0, -1);
					} else {
						$is_current_pool = false;
					}

					$pools[] = [
						'name' => $pool[0],
						'url' => $pool[1],
						'is_current_pool' => $is_current_pool,
					];
				}
			}
		}

		if (!$current_pool_name || count($pools) < 2)
			$pools = [];

		return view('home', [
			'message' => $message,
			'pools' => $pools,
			'current_pool_name' => $current_pool_name,
		]);
	}
}
