<?php

namespace App\Pool\Miners;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	protected $list = [];

	public function getNumberOfMiners()
	{
		return count($this->list);
	}

	public function getNumberOfActiveMiners()
	{
		$active = 0;

		foreach ($this->list as $miner) {
			if ($miner->getStatus() === 'active')
				$active++;
		}

		return $active;
	}

	public function getTotalNopaidShares()
	{
		$total = 0;
		foreach ($this->list as $miner)
			$total += $miner->getNopaidShares();

		return $total;
	}

	public function getMiner($address)
	{
		foreach ($this->list as $miner) {
			if ($miner->getAddress() === $address)
				return $miner;
		}

		return null;
	}

	protected function parse()
	{
		array_shift($this->lines);
		array_shift($this->lines);
		array_shift($this->lines);

		foreach ($this->lines as $line) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 6)
				continue;

			if ($parts[0] === '-1.')
				continue;

			$this->list[] = new Miner($parts[1], $parts[2], $parts[3], $parts[4], $parts[5]);
		}
	}
}
