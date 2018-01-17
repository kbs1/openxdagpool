<?php

namespace App\Pool;

class DataReader
{
	public function getStatistics()
	{
		return (string) @file_get_contents(env('STATS_ADDR'));
	}

	public function getMiners()
	{
		return (string) @file_get_contents(env('MINERS_LIST_ADDR'));
	}

	public function getBalances()
	{
		return (string) @file_get_contents(env('BALANCES_ADDR'));
	}
}
