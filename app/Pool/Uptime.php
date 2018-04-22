<?php

namespace App\Pool;

use Setting;
use Carbon\Carbon;

class Uptime
{
	public function getReadableUptime()
	{
		$names = ['y', 'm', 'w', 'd', 'h', 'm', 's'];
		$parts = $this->getUptime();
		$result = [];

		foreach ($parts as $key => $part) {
			if ($part > 0 || count($result) > 0)
				$result[] = sprintf('%d' . $names[$key], $part);

			if (count($result) >= 2)
				break;
		}

		return $result ? implode('', $result) : '0s';
	}

	public function getExactUptime()
	{
		$names = ['years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds'];
		$parts = $this->getUptime();
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

	protected function getUptime()
	{
		try {
			$created_at = Setting::get('pool_created_at');
			if (!$created_at)
				$created_at = Carbon::now();
			else
				$created_at = Carbon::createFromFormat('Y-m-d H:i:s', $created_at . '00:00:00');
		} catch (\Exception $ex) {
			$created_at = Carbon::now();
		}

		$now = Carbon::now();
		$seconds = $now->diffInSeconds($created_at);

		$years = floor($seconds / 31536000); // approximate years
		$months = floor(($seconds - $years * 31536000) / 2592000); // approximate months
		$weeks = floor(($seconds - $years * 31536000 - $months * 2592000) / 604800);
		$days = floor(($seconds - $years * 31536000 - $months * 2592000 - $weeks * 604800) / 86400);
		$hours = floor(($seconds - $years * 31536000 - $months * 2592000 - $weeks * 604800 - $days * 86400) / 3600);
		$minutes = floor($seconds / 60 % 60);
		$seconds = floor($seconds % 60);

		return [$years, $months, $weeks, $days, $hours, $minutes, $seconds];
	}
}
