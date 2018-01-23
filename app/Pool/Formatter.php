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
		$size = [' h/s', ' Kh/s', ' Mh/s', ' Gh/s', ' Th/s', ' Ph/s', ' Eh/s', ' Zh/s', ' Yh/s'];
		$factor = floor((strlen(intval($rate)) - 1) / 3);

		return floatval(sprintf("%.2f", $rate / pow(1000, $factor))) . @$size[$factor];
	}
}
