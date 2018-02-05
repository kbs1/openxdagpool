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
		return env('FEES_PERCENT', 0.5) . '% pool fee, ' . env('REWARD_PERCENT', 12) . '% reward for found block, ' . env('DIRECT_PERCENT', 7) . '% reward for direct contributions to found block, ' . env('FUND_PERCENT', 0.5) . '% donation to community fund (' . (100 - env('FEES_PERCENT') - env('REWARD_PERCENT') - env('DIRECT_PERCENT') - env('FUND_PERCENT')) . '% of found block reward is split amongst all connected miners whether they contributed to the current block or not)';
	}
}
