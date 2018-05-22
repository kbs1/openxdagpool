<?php

namespace App\Pool;

class DataReader
{
	public function getState()
	{
		return @fopen($this->getPath('STATE'), 'r');
	}

	public function getStatistics()
	{
		return @fopen($this->getPath('STATS'), 'r');
	}

	public function getBlocks()
	{
		return @fopen($this->getPath('BLOCKS'), 'r');
	}

	public function getMiners()
	{
		return @fopen($this->getPath('MINERS'), 'r');
	}

	public function getPayouts()
	{
		return @fopen($this->getPath('PAYOUTS'), 'r');
	}

	protected function getPath($env_name)
	{
		$path = env($env_name);

		if (substr($path, 0, 2) === './')
			return base_path($path);

		return $path;
	}
}
