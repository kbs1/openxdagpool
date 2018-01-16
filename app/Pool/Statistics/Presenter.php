<?php

namespace App\Pool\Statistics;

class Presenter
{
	protected $parser;

	public function __construct(Parser $parser)
	{
		$this->parser = $parser;
	}

	public function getHashrate()
	{
		$rate = $this->parser->getHashrate();

		$size = [' h/s', ' Kh/s', ' Mh/s', ' Gh/s', ' Th/s', ' Ph/s', ' Eh/s', ' Zh/s', ' Yh/s'];
		$factor = floor((strlen($rate) - 1) / 3);

		return floatval(sprintf("%.2f", $rate / pow(1000, $factor))) . @$size[$factor];
	}

	public function getNumberOfBlocks()
	{
		return $this->parser->getNumberOfBlocks();
	}

	public function getNumberOfMainBlocks()
	{
		return $this->parser->getNumberOfMainBlocks();
	}

	public function getDifficulty()
	{
		return $this->parser->getDifficulty();
	}
}
