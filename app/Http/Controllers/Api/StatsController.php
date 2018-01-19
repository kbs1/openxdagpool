<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Pool\{DataReader, Config, Uptime};
use App\Pool\Statistics\{Parser as StatisticsParser, Presenter as StatisticsPresenter, Stat as PoolStat};
use App\Pool\Miners\{Parser as MinersParser, Presenter as MinersPresenter};

use Carbon\Carbon;

class StatsController extends Controller
{
	protected $reader, $config, $uptime;

	public function __construct(DataReader $reader, Config $config, Uptime $uptime)
	{
		$this->reader = $reader;
		$this->config = $config;
		$this->uptime = $uptime;
	}

	public function index()
	{
		$stats = new StatisticsPresenter(new StatisticsParser($this->reader->getStatistics()));
		$miners = new MinersPresenter(new MinersParser($this->reader->getMiners()));

		return response()->json([
			'pool_hashrate' => $stats->getPoolHashrate(),
			'network_hashrate' => $stats->getNetworkHashrate(),
			'blocks' => $stats->getNumberOfBlocks(),
			'main_blocks' => $stats->getNumberOfMainBlocks(),
			'difficulty' => $stats->getReadableDifficulty(),
			'difficulty_exact' => $stats->getExactDifficulty(),
			'supply' => $stats->getSupply(),

			'miners' => $miners->getNumberOfActiveMiners(),

			'fees' => $this->config->getFees(),
			'config' => $this->config->getConfig(),

			'uptime' => $this->uptime->getReadableUptime(),
			'uptime_exact' => $this->uptime->getExactUptime(),
		]);
	}

	public function detailed()
	{
		$pool_hashrate = ['x' => [], 'Hashrate (Gh/s)' => []];
		$active_miners = ['x' => [], 'Active miners' => []];
		$network_hashrate = ['x' => [], 'Hashrate (Gh/s)' => []];

		$stats = PoolStat::orderBy('id', 'asc')->where('created_at', '>', Carbon::now()->subDays(7))->get();

		foreach ($stats as $stat) {
			$datetime = $stat->created_at->subMinutes(5)->format('Y-m-d H:i');

			$pool_hashrate['x'][] = $datetime;
			$active_miners['x'][] = $datetime;
			$network_hashrate['x'][] = $datetime;

			$pool_hashrate['Hashrate (Gh/s)'][] = $stat->pool_hashrate / 1000000000;
			$network_hashrate['Hashrate (Gh/s)'][] = $stat->network_hashrate / 1000000000;

			$active_miners['Active miners'][] = $stat->active_miners;
		}

		return response()->json([
			'pool_hashrate' => $pool_hashrate,
			'active_miners' => $active_miners,
			'network_hashrate' => $network_hashrate,
		]);
	}
}
