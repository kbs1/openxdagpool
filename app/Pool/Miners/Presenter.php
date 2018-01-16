<?php

namespace App\Pool\Miners;

class Presenter
{
	protected $parser;

	public function __construct(Parser $parser)
	{
		$this->parser = $parser;
	}

	public function getNumberOfMiners()
	{
		return number_format($this->parser->getNumberOfMiners(), 0, '.', ',');
	}

	public function getNumberOfActiveMiners()
	{
		return number_format($this->parser->getNumberOfActiveMiners(), 0, '.', ',');
	}
}
