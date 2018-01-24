<?php

namespace App\Pool\Miners;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	public function getNumberOfMiners()
	{
		$count = 0;

		$this->forEachMinerLine(function($parts) use (&$count) {
			$count++;
		});

		return $count;
	}

	public function getNumberOfActiveMiners()
	{
		$active = 0;

		$this->forEachMinerLine(function($parts) use (&$active) {
			if ($parts[2] === 'active')
				$active++;
		});

		return $active;
	}

	public function getTotalUnpaidShares()
	{
		$total = 0;

		$this->forEachMinerLine(function($parts) use (&$total) {
			$total += $parts[5];
		});

		return $total;
	}

	public function getMiner($address)
	{
		$miner = null;

		$this->forEachMinerLine(function($parts) use ($address, &$miner) {
			if ($parts[1] === $address) {
				if (!$miner) {
					$miner = new Miner($parts[1], $parts[2], $parts[3], $parts[4], $parts[5]);
				} else {
					$miner->addIpAndPort($parts[3]);
					$miner->addUnpaidShares($parts[5]);

					if ($miner->getStatus() !== 'active' && $parts[2] === 'active')
						$miner->setStatus($parts[2]);
				}
			}
		});

		return $miner;
	}

	protected function forEachMinerLine(callable $callback, $skip = 0)
	{
		$this->forEachLine(function($line) use ($callback) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 6)
				return;

			if ($parts[0] === '-1.')
				return;

			$callback($parts);
		}, $skip);
	}
}
