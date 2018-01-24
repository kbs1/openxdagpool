<?php

namespace App\Pool;

class BalancesParser extends BaseParser
{
	public function getBalance($address)
	{
		$balance = 0;

		$this->forEachBalanceLine(function($parts) use ($address, &$balance) {
			if ($parts[0] === $address)
				$balance = $parts[1];
		});

		return $balance;
	}

	protected function forEachBalanceLine(callable $callback, $skip = 0)
	{
		$this->forEachLine(function($line) use ($callback) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 2)
				return;

			if (strlen($parts[0]) !== 32)
				return;

			$callback($parts);
		}, $skip);
	}
}
