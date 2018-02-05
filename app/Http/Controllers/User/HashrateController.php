<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Pool\Formatter;
use App\Pool\Statistics\Stat as PoolStat;
use Auth;

class HashrateController extends Controller
{
	protected $format;

	public function __construct(Formatter $format)
	{
		$this->middleware('auth');
		$this->middleware('active');

		$this->format = $format;
	}

	public function minerGraph($uuid, $type)
	{
		if (($miner = Auth::user()->miners()->where('uuid', $uuid)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		if (!in_array($type, ['latest', 'daily']))
			$type = 'latest';

		return view('user.hashrate.miner-graph', [
			'miner' => $miner,
			'hashrate' => $this->format->hashrate($miner->getHashrateSum),
			'graph_data' => $this->getGraphData($miner, $type),
			'type' => $type,
			'activeTab' => 'miners',
		]);
	}

	public function userGraph($type)
	{
		$user = Auth::user();

		return view('user.hashrate.user-graph', [
		'hashrate' => $this->format->hashrate($user->hashrate),
			'graph_data' => $this->getGraphData($user, $type),
			'type' => $type,
			'activeTab' => 'hashrate',
		]);
	}

	protected function getGraphData($model, $type)
	{
		$graph = ['x' => [], 'Hashrate (Mh/s)' => []];

		if ($type == 'daily')
			$stats = $model->getDailyHashrate();
		else
			$stats = $model->getLatestHashrate();

		foreach ($stats as $stat) {
			$graph['x'][] = $stat->date;
			$graph['Hashrate (Mh/s)'][] = $stat->hashrate / 1000000;
		}

		return json_encode($graph);
	}
}
