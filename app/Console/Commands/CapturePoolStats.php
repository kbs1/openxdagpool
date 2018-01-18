<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Statistics\Parser as StatisticsParser;
use App\Pool\Miners\Parser as MinersParser;

use App\Pool\Statistics\Stat;
use Carbon\Carbon;

class CapturePoolStats extends Command
{
	protected $signature = 'stats:pool';
	protected $description = 'Capture pool stats if latest stat is older than 4 minutes, 55 seconds';

	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$latest = Stat::orderBy('id', 'desc')->first();

		if ($latest && $latest->created_at > Carbon::now()->subMinutes(4)->subSeconds(55)) {
			$this->error('Newest stat record is not older than 4 minutes and 55 seconds.');
			return 1;
		}

		$stats = new StatisticsParser($this->reader->getStatistics());
		$miners = new MinersParser($this->reader->getMiners());

		$stat = new Stat([
			'pool_hashrate' => $stats->getPoolHashrate(),
			'network_hashrate' => $stats->getNetworkHashrate(),
			'active_miners' => $miners->getNumberOfActiveMiners(),
		]);

		$stat->save();

		$this->info('Saved successfully.');
	}
}
