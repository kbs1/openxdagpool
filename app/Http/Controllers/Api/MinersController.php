<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Pool\DataReader;
use App\Pool\Statistics\{Parser as StatisticsParser, Presenter as StatisticsPresenter};
use App\Pool\Miners\Parser as MinersParser;
use App\Pool\Balances\Parser as BalancesParser;

use App\Miners\Miner;

class MinersController extends Controller
{
	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->middleware('auth');
		$this->reader = $reader;
	}

	public function list(Request $request)
	{
		$user = Auth::user();

		$stats = new StatisticsParser($this->reader->getStatistics());
		$stats_presenter = new StatisticsPresenter($stats);
		$miners = new MinersParser($this->reader->getMiners());
		$balances = new BalancesParser($this->reader->getBalances());

		$pool_hashrate = (float) $stats->getPoolHashrate();
		$total_nopaid_shares = (float) $miners->getTotalNopaidShares();

		$result = [];

		foreach ((array) $request->input('uuid') as $uuid) {
			$miner = Miner::where('uuid', $uuid)->where('user_id', $user->id)->first();

			if (!$miner) continue;

			if (($pool_miner = $miners->getMiner($miner->address)) === null) {
				$result[$uuid] = [
					'status' => 'offline',
					'ip_and_port' => null,
					'hashrate' => '0 H/s',
					'nopaid_shares' => '0.000000',
					'balance' => $balances->getBalance($miner->address) . ' XDAG',
				];

				continue;
			}

			$hashrate = 0;
			if ($total_nopaid_shares > 0) {
				$nopaid_proportion = $miner->average_unpaid_shares / $total_nopaid_shares;
				if (!is_nan($nopaid_proportion) && !is_infinite($nopaid_proportion)) {
					$hashrate = $nopaid_proportion * $pool_hashrate;
				}
			}

			$result[$uuid] = [
				'status' => $pool_miner->getStatus(),
				'ip_and_port' => $pool_miner->getIpsAndPort(),
				'hashrate' => $stats_presenter->formatHashrate($hashrate),
				'nopaid_shares' => $pool_miner->getNopaidShares(),
				'balance' => $balances->getBalance($miner->address) . ' XDAG',
			];
		}

		return response()->json($result);
	}
}
