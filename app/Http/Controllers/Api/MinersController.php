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

		$stats_presenter = new StatisticsPresenter(new StatisticsParser($this->reader->getStatistics()));
		$miners_parser = new MinersParser($this->reader->getMiners());
		$balances_parser = new BalancesParser($this->reader->getBalances());

		$total_unpaid_shares = (float) $miners_parser->getTotalUnpaidShares();

		$result = [];

		foreach ((array) $request->input('uuid') as $uuid) {
			$miner = Miner::where('uuid', $uuid)->where('user_id', $user->id)->first();

			if (!$miner) continue;

			if (($pool_miner = $miners_parser->getMiner($miner->address)) === null) {
				$result[$uuid] = [
					'status' => 'offline',
					'ip_and_port' => null,
					'hashrate' => '0 H/s',
					'unpaid_shares' => '0.000000',
					'balance' => $balances_parser->getBalance($miner->address) . ' XDAG',
				];

				continue;
			}

			$hashrate = $miner->getEstimatedHashrate($total_unpaid_shares);

			$result[$uuid] = [
				'status' => $pool_miner->getStatus(),
				'ip_and_port' => $pool_miner->getIpsAndPort(),
				'hashrate' => $stats_presenter->formatHashrate($hashrate),
				'unpaid_shares' => $pool_miner->getUnpaidShares(),
				'balance' => $balances_parser->getBalance($miner->address) . ' XDAG',
			];
		}

		return response()->json($result);
	}
}
