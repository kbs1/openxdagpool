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
		return env('FEES_PERCENT', 0.5) . '% pool fee, ' . env('REWARD_PERCENT', 12) . '% reward for block, ' . env('DIRECT_PERCENT', 7) . '% reward for contribution, ' . env('FUND_PERCENT', 0.5) . '% donation to community fund';
	}
}
