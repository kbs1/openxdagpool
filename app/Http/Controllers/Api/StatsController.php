<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Pool\{DataReader, Config, Uptime};
use App\Pool\Statistics\{Parser as StatisticsParser, Presenter as StatisticsPresenter};
use App\Pool\Miners\{Parser as MinersParser, Presenter as MinersPresenter};

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
}
