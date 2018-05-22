<?php

namespace App\Pool;

class Formatter
{
	public function balance($value)
	{
		return number_format($value, 2, '.', ',') . ' XDAG';
	}

	public function fullBalance($value)
	{
		return number_format($value, 9, '.', ',') . ' XDAG';
	}

	public function wholeBalance($value)
	{
		return number_format($value, 0, '.', ',') . ' XDAG';
	}

	public function hashrate($rate)
	{
		$units = ['h/s', 'Kh/s', 'Mh/s', 'Gh/s', 'Th/s', 'Ph/s', 'Eh/s', 'Zh/s', 'Yh/s'];
		$unit = intval(log(abs(intval($rate)), 1024));

		if (array_key_exists($unit, $units))
			return sprintf('%.2f %s', $rate / pow(1024, $unit), $units[$unit]);

		return $rate;
	}
}
