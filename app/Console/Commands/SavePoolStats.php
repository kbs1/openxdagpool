<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Statistics\{Parser as StatisticsParser, Stat};
use App\Miners\Miner;

class SavePoolStats extends Command
{
	protected $signature = 'stats:pool';
	protected $description = 'Inserts latest pool stats.';

	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$stats = new StatisticsParser($this->reader->getStatistics());

		$stat = new Stat([
			'pool_hashrate' => $stats->getPoolHashrate(),
			'network_hashrate' => $stats->getNetworkHashrate(),
			'active_miners' => Miner::where('status', '!=', 'offline')->count(),
		]);

		$stat->save();

		$this->info('SavePoolStats completed successfully.');
	}
}
