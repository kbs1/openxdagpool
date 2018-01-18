<?php

namespace App\Pool;

class DataReader
{
	public function getStatistics()
	{
		return (string) @file_get_contents($this->getPath('STATS_ADDR'));
	}

	public function getMiners()
	{
		return (string) @file_get_contents($this->getPath('MINERS_LIST_ADDR'));
	}

	public function getBalances()
	{
		return (string) @file_get_contents($this->getPath('BALANCES_ADDR'));
	}

	protected function getPath($env_name)
	{
		$path = env($env_name);

		if (substr($path, 0, 2) === './')
			return base_path($path);

		return $path;
	}
}
