<?php

namespace App\Pool\Payouts;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	public function forEachPayoutLine(callable $callback)
	{
		$this->forEachLine(function($line) use ($callback) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 12)
				return;

			$callback(new Payout($parts[0] . ' ' . $parts[1], substr(substr($parts[2], 1), 0, -1), $parts[6], $parts[8], $parts[10]));
		});
	}
}
