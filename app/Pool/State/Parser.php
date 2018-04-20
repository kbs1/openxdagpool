<?php

namespace App\Pool\State;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	protected $lines, $pool_version = 'unknown', $pool_state = 'unknown';

	public function __construct($handle)
	{
		parent::__construct($handle);
		$this->read();
	}

	public function getPoolVersion()
	{
		return $this->pool_version;
	}

	public function getPoolState()
	{
		return $this->pool_state;
	}

	public function isNormalPoolState()
	{
		return stripos($this->getPoolState(), 'normal operation') !== false;
	}

	protected function read()
	{
		$line_number = 0;
		$this->forEachLine(function($line) use (&$line_number) {
			if ($line_number == 0)
				$this->pool_version = $line;

			if ($line_number == 1)
				$this->pool_state = $line;

			$line_number++;
		});
	}
}
