<?php

namespace App\Pool\Blocks;

use App\Pool\BaseParser;
use App\Pool\Config;

class Parser extends BaseParser
{
	public function forEachBlockLine(callable $callback)
	{
		$config = new Config;
		$fee_percent = (float) $config->getFees();

		$this->forEachLine(function($line) use ($callback, $fee_percent) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 8)
				return;

			$t = explode('=', $parts[6]);
			if (count($t) != 2)
				return;
			$t = $t[1];

			$res = explode('=', $parts[7]);
			if (count($res) != 2)
				return;
			$res = $res[1];

			if ($fee_percent > 100 || $fee_percent < 0)
				$fee_percent = 0;

			$payout = 1024 * ((100 - $fee_percent) / 100);
			$fee = 1024 * ($fee_percent / 100);

			$callback(new Block($parts[0] . ' ' . $parts[1], substr(substr($parts[2], 1), 0, -1), $parts[5], $t, $res, $payout, $fee));
		});
	}
}
