<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class StatsController extends Controller
{
	public function index()
	{
		return response()->json([
			'hashrate' => $this->getHashrate(),
			'miners' => $this->getMinersCount(),
			'fees' => $this->getFees(),
			'uptime' => $this->getReadableUptime(),
			'uptime_exact' => $this->getExactUptime(),
		]);
	}

	protected function getHashrate()
	{
		$rate = 0;

		$size = [' h/s', ' Kh/s', ' Mh/s', ' Gh/s', ' Th/s', ' Ph/s', ' Eh/s', ' Zh/s', ' Yh/s'];
		$factor = floor((strlen($rate) - 1) / 3);

		return floatval(sprintf("%.2f", $rate / pow(1000, $factor))) . @$size[$factor];
	}

	protected function getMinersCount()
	{
		$count = 0;

		return number_format($count, 0, '.', ',');
	}

	protected function getFees()
	{
		return env('FEES_PERCENT', 0.5) . '%';
	}

	protected function getReadableUptime()
	{
		$names = ['y', 'm', 'w', 'd', 'h', 'm', 's'];
		$parts = $this->parseSystemUptime();

		foreach ($parts as $key => $part)
			if ($part > 0)
				return sprintf('%d' . $names[$key], $part);

		return '0s';
	}

	protected function getExactUptime()
	{
		$names = ['years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds'];
		$parts = $this->parseSystemUptime();
		$format = '';

		foreach ($parts as $key => $part)
			if ($part > 0 || $format !== '')
				$format .= '%d ' . $names[$key] . ', ';
			else
				unset($parts[$key]);

		if ($format !== '') {
			$format = substr($format, 0, -2);
			array_unshift($parts, $format);
			return call_user_func_array('sprintf', array_values($parts));
		}

		return '0 seconds';
	}

	protected function parseSystemUptime()
	{
		$data = @file_get_contents('/proc/uptime');

		if ($data === false)
			$data = '0 0';

		$data = explode(' ', $data);
		$seconds = floor($data[0]);

		$years = floor($seconds / 217728000); // approximate years
		$months = floor(($seconds - $years * 217728000) / 18144000); // approximate months
		$weeks = floor(($seconds - $years * 217728000 - $months * 18144000) / 604800);
		$days = floor(($seconds - $years * 217728000 - $months * 18144000 - $weeks * 604800) / 86400);
		$hours = floor(($seconds - $years * 217728000 - $months * 18144000 - $weeks * 604800 - $days * 86400) / 3600);
		$minutes = floor($seconds / 60 % 60);
		$seconds = floor($seconds % 60);

		return [$years, $months, $weeks, $days, $hours, $minutes, $seconds];
	}
}
