<?php

namespace App\Pool\Statistics;

class Presenter
{
	protected $parser;

	public function __construct(Parser $parser)
	{
		$this->parser = $parser;
	}

	public function getReadableDifficulty()
	{
		return substr($this->parser->getDifficulty(), 0, 3) . '...' . substr($this->parser->getDifficulty(), -3);
	}

	public function getExactDifficulty()
	{
		return $this->parser->getDifficulty();
	}
}
