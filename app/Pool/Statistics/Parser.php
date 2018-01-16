<?php

namespace App\Pool\Statistics;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	protected $hashrate = 0, $blocks = 0, $main_blocks = 0, $difficulty = 0;

	public function getHashrate()
	{
		return $this->hashrate;
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

	protected function getLine($marker)
	{
		$marker .= ': ';

		foreach ($this->data as $line) {
			if (substr($line, 0, strlen($marker)) === $marker)
				return substr($line, strlen($marker));
		}

		return false;
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
		$this->hashrate = $this->getValue('hour hashrate MHs') * 1000000;
		$this->blocks = $this->getValue('blocks');
		$this->main_blocks = $this->getValue('main blocks');
		$this->difficulty = $this->getValue('chain difficulty');
	}
}
