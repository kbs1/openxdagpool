<?php

namespace App\Pool;

class Uptime
{
	public function getReadableUptime()
	{
		$names = ['y', 'm', 'w', 'd', 'h', 'm', 's'];
		$parts = $this->parseSystemUptime();

		foreach ($parts as $key => $part)
			if ($part > 0)
				return sprintf('%d' . $names[$key], $part);

		return '0s';
	}

	public function getExactUptime()
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
		$seconds = floor($data[0]) + max(0, (int) env('UPTIME_OFFSET'));

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
