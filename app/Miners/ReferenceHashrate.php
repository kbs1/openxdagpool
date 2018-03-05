<?php

namespace App\Miners;

use Setting;
use App\Pool\Statistics\Stat as PoolStat;
use App\Pool\Miners\Parser as MinersParser;

class ReferenceHashrate
{
	protected $miner_address, $target_hashrate;

	public function __construct()
	{
		$this->miner_address = Setting::get('reference_miner_address');
		$this->target_hashrate = Setting::get('reference_miner_hashrate') * 1073741824; // convert Gh/s to H/s
	}

	public function compute(MinersParser $miners_parser, PoolStat $when)
	{
		if (!$this->shouldBeUsed())
			return $this->resetCoefficient();

		$pool_miner = $miners_parser->getMiner($this->miner_address);
		if (!$pool_miner || $pool_miner->getStatus() !== 'active')
			return $this->resetCoefficient();

		$miner = Miner::where('address', $this->miner_address)->first();
		if (!$miner)
			return $this->resetCoefficient();

		$miner_hashrate = $miner->getEstimatedHashrate($when, false);
		if ($miner_hashrate <= 0)
			return $this->resetCoefficient();

		Setting::set('reference_miner_coefficient', $this->target_hashrate / $miner_hashrate);
	}

	public function getCoefficient()
	{
		return Setting::get('reference_miner_coefficient', 1);
	}

	public function resetCoefficient()
	{
		return Setting::set('reference_miner_coefficient', 1);
	}

	protected function shouldBeUsed()
	{
		return $this->miner_address && $this->target_hashrate > 0;
	}
}
