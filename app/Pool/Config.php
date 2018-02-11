<?php

namespace App\Pool;

use Setting;

class Config
{
	public function getFees()
	{
		return Setting::get('fees_percent', 0.5) . '%';
	}

	public function getConfig()
	{
		return Setting::get('fees_percent', 0.5) . '% pool fee, ' . Setting::get('reward_percent', 1) . '% reward for found block, ' . Setting::get('direct_percent', 1) . '% reward for direct contributions to found block, ' . Setting::get('fund_percent', 0.5) . '% donation to community fund (' . (100 - Setting::get('fees_percent', 0.5) - Setting::get('reward_percent', 1) - Setting::get('direct_percent', 1) - Setting::get('fund_percent', 0.5)) . '% of found block reward is split amongst all connected miners whether they contributed to the current block or not)';
	}
}
