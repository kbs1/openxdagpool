<?php

namespace App\Pool;

class BalancesParser extends BaseParser
{
	protected $list = [];

	public function getBalance($address)
	{
		return $this->list[$address] ?? 0;
	}

	public function addressExists($address)
	{
		return isset($this->list[$address]);
	}

	protected function parse()
	{
		foreach ($this->lines as $line) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 2)
				continue;

			if (strlen($parts[0]) !== 32)
				continue;

			$this->list[$parts[0]] = $parts[1];
		}
	}
}
