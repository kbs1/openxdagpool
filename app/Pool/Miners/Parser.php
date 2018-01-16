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

	protected function getValue($marker)
	{
		$line = $this->getLine($marker);

		if ($line === false)
			return 0;

		$line = explode(' of ', $line);
		return $line[0];
	}

	protected function parse()
	{
		array_shift($this->data);
		array_shift($this->data);
		array_shift($this->data);

		foreach ($this->data as $line) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 6)
				continue;

			if ($parts[0] === '-1.')
				continue;

			$this->list[] = new Miner($parts[1], $parts[2], $parts[3], $parts[4], $parts[5]);
		}
	}
}
