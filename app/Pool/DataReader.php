<?php

namespace App\Pool;

class DataReader
{
	public function getStatistics()
	{
		return (string) @fopen($this->getPath('STATS'), 'r');
	}

	public function getMiners()
	{
		return (string) @fopen($this->getPath('MINERS'), 'r');
	}

	public function getBalances()
	{
		return (string) @fopen($this->getPath('BALANCES'), 'r');
	}

	public function getPayouts()
	{
		return (string) @fopen($this->getPath('PAYOUTS'), 'r');
	}

	protected function getPath($env_name)
	{
		$path = env($env_name);

		if (substr($path, 0, 2) === './')
			return base_path($path);

		return $path;
	}
}
