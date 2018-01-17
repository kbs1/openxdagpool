<?php

namespace App\Pool\Statistics;

class Presenter
{
	protected $parser;

	public function __construct(Parser $parser)
	{
		$this->parser = $parser;
	}

	public function getPoolHashrate()
	{
		return $this->formatHashrate($this->parser->getPoolHashrate());
	}

	public function getNetworkHashrate()
	{
		return $this->formatHashrate($this->parser->getNetworkHashrate());
	}

	public function getNumberOfBlocks()
	{
		return $this->parser->getNumberOfBlocks();
	}

	public function getNumberOfMainBlocks()
	{
		return $this->parser->getNumberOfMainBlocks();
	}

	public function getReadableDifficulty()
	{
		return substr($this->parser->getDifficulty(), 0, 3) . '...' . substr($this->parser->getDifficulty(), -3);
	}

	public function getExactDifficulty()
	{
		return $this->parser->getDifficulty();
	}

	public function getSupply()
	{
		return number_format($this->parser->getSupply(), 0, '.', ',') . ' XDAG';
	}

	public function formatHashrate($rate)
	{
		$size = [' h/s', ' Kh/s', ' Mh/s', ' Gh/s', ' Th/s', ' Ph/s', ' Eh/s', ' Zh/s', ' Yh/s'];
		$factor = floor((strlen(intval($rate)) - 1) / 3);

		return floatval(sprintf("%.2f", $rate / pow(1000, $factor))) . @$size[$factor];
	}
}
