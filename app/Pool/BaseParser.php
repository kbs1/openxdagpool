<?php

namespace App\Pool;

abstract class BaseParser
{
	protected $lines = [];

	public function __construct($data)
	{
		$this->parseLines($data);
		$this->parse();
	}

	protected function parseLines($data)
	{
		$lines = explode("\n", $data);

		if (count($lines) < 8)
			return;

		array_shift($lines);

		foreach ($lines as &$line) {
			if (substr($line, 0, 6) === 'xdag> ')
				$line = substr($line, 6);

			$line = trim($line);
		}
		unset($line);

		$lines = array_values(array_filter($lines));

		$this->lines = $lines;
	}

	abstract protected function parse();
}
