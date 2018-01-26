<?php

namespace App\Pool;

class Config
{
	public function getFees()
	{
		return env('FEES_PERCENT', 0.5) . '%';
	}

	public function getConfig()
	{
		return env('FEES_PERCENT', 0.5) . '% pool fee, ' . env('REWARD_PERCENT', 12) . '% reward for connected miners, ' . env('DIRECT_PERCENT', 7) . '% reward for found block, ' . env('FUND_PERCENT', 0.5) . '% donation to community fund';
	}
}
