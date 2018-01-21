<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Pool\{DataReader, Config, Uptime};
use App\Pool\Statistics\{Parser as StatisticsParser, Presenter as StatisticsPresenter, Stat as PoolStat};
use App\Pool\Miners\{Parser as MinersParser, Presenter as MinersPresenter};
use App\Pool\Balances\Parser as BalancesParser;

use App\Users\User;

use Carbon\Carbon;
use Auth;

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
		$stats_presenter = new StatisticsPresenter($stats_parser = new StatisticsParser($this->reader->getStatistics()));
		$miners_presenter = new MinersPresenter($miners_parser = new MinersParser($this->reader->getMiners()));
		$balances_parser = new BalancesParser($this->reader->getBalances());

		$pool_hashrate = (float) $stats_parser->getPoolHashrate();
		$total_unpaid_shares = (float) $miners_parser->getTotalUnpaidShares();

		$user_stats = [];

		if ($user = Auth::user()) {
			$user_hashrate = $user_miners = $user_balance = 0;
			$hashrates = [];

			foreach ($user->miners as $miner) {
				$user_balance += $balances_parser->getBalance($miner->address);

				if (($pool_miner = $miners_parser->getMiner($miner->address)) === null) continue;

				$user_hashrate += $miner->getEstimatedHashrate($total_unpaid_shares);
				$user_miners += $pool_miner->getMachinesCount();
			}

			$user_stats = [
				'user_hashrate' => $stats_presenter->formatHashrate($user_hashrate),
				'user_miners' => $user_miners,
				'user_balance' => $user_balance,
				'user_rank' => '#1',
			];

			$hashrates[] = $user_hashrate;
			$current_user_hashrate = $user_hashrate;

			foreach (User::where('id', '!=', $user->id)->with('miners')->get() as $user) {
				$user_hashrate = 0;
				foreach ($user->miners as $miner) {
					if (($pool_miner = $miners_parser->getMiner($miner->address)) === null) continue;
					$user_hashrate += $miner->getEstimatedHashrate($total_unpaid_shares);
				}

				$hashrates[] = $user_hashrate;
			}

			rsort($hashrates);
			$user_stats['user_rank'] = '#' . (array_search($current_user_hashrate, $hashrates) + 1);
		}

		return response()->json([
			'pool_hashrate' => $stats_presenter->getPoolHashrate(),
			'network_hashrate' => $stats_presenter->getNetworkHashrate(),
			'blocks' => $stats_presenter->getNumberOfBlocks(),
			'main_blocks' => $stats_presenter->getNumberOfMainBlocks(),
			'difficulty' => $stats_presenter->getReadableDifficulty(),
			'difficulty_exact' => $stats_presenter->getExactDifficulty(),
			'supply' => $stats_presenter->getSupply(),

			'miners' => $miners_presenter->getNumberOfActiveMiners(),

			'fees' => $this->config->getFees(),
			'config' => $this->config->getConfig(),

			'uptime' => $this->uptime->getReadableUptime(),
			'uptime_exact' => $this->uptime->getExactUptime(),
		] + $user_stats);
	}

	public function detailed()
	{
		$pool_hashrate = ['x' => [], 'Pool hashrate (Gh/s)' => []];
		$active_miners = ['x' => [], 'Active pool miners' => []];
		$network_hashrate = ['x' => [], 'Network nashrate (Gh/s)' => []];

		$stats = PoolStat::orderBy('id', 'asc')->where('created_at', '>', Carbon::now()->subDays(7))->get();

		foreach ($stats as $stat) {
			$datetime = $stat->created_at->subMinutes(5)->format('Y-m-d H:i');

			$pool_hashrate['x'][] = $datetime;
			$active_miners['x'][] = $datetime;
			$network_hashrate['x'][] = $datetime;

			$pool_hashrate['Pool hashrate (Gh/s)'][] = $stat->pool_hashrate / 1000000000;
			$network_hashrate['Network ashrate (Gh/s)'][] = $stat->network_hashrate / 1000000000;

			$active_miners['Active pool miners'][] = $stat->active_miners;
		}

		return response()->json([
			'pool_hashrate' => $pool_hashrate,
			'active_miners' => $active_miners,
			'network_hashrate' => $network_hashrate,
		]);
	}
}
