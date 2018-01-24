<?php

namespace App\Pool\Statistics;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	protected $lines, $pool_hashrate = 0, $network_hashrate = 0, $blocks = 0, $main_blocks = 0, $difficulty = 0, $supply = 0;

	public function __construct($handle)
	{
		parent::__construct($handle);
		$this->read();
		$this->parse();
	}

	public function getPoolHashrate()
	{
		return $this->pool_hashrate;
	}

	public function getNetworkHashrate()
	{
		return $this->network_hashrate;
	}

	public function getNumberOfBlocks()
	{
		return $this->blocks;
	}

	public function getNumberOfMainBlocks()
	{
		return $this->main_blocks;
	}

	public function getDifficulty()
	{
		return $this->difficulty;
	}

	public function getSupply()
	{
		return $this->supply;
	}

	protected function read()
	{
		$this->lines = [];
		$this->forEachLine(function($line) {
			$this->lines[] = $line;
		});
	}

	protected function getLine($marker)
	{
		$marker .= ': ';

		foreach ($this->lines as $line) {
			if (substr($line, 0, strlen($marker)) === $marker)
				return substr($line, strlen($marker));
		}

		return false;
	}

	protected function getValue($marker, $network_value = false)
	{
		$line = $this->getLine($marker);

		if ($line === false)
			return 0;

		$line = explode(' of ', $line);
		return $network_value === false ? $line[0] : ($line[1] ?? $line[0]);
	}

	protected function parse()
	{
		$this->pool_hashrate = $this->getValue('hour hashrate MHs') * 1000000;
		$this->network_hashrate = $this->getValue('hour hashrate MHs', true) * 1000000;
		$this->blocks = $this->getValue('blocks');
		$this->main_blocks = $this->getValue('main blocks');
		$this->difficulty = $this->getValue('chain difficulty');
		$this->supply = $this->getValue('XDAG supply');
	}
}
