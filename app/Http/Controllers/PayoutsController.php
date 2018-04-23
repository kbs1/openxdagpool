<?php

namespace App\Http\Controllers;

use App\Http\Controllers\User\PayoutsController as UserPayoutsController;

use App\Http\Requests\CheckBalance;
use App\Miners\Miner;

use Excel;

class PayoutsController extends UserPayoutsController
{
	public function __construct()
	{
		// no middleware for this controller
	}

	public function addressPayoutsGraph(CheckBalance $request)
	{
		$miner = $this->getMiner($request);

		return view('payouts.miner-payouts-graph', [
			'miner' => $miner,
			'graph_data' => $this->getGraphData($miner->getDailyPayouts(), $sum),
			'payouts_sum' => $sum,
		]);
	}

	public function addressPayoutsListing(CheckBalance $request)
	{
		$miner = $this->getMiner($request);

		return view('payouts.miner-payouts-listing', [
			'miner' => $miner,
			'payouts' => $miner->getPayoutsListing($request->input('page'))->appends(['address' => $miner->address]),
			'payouts_sum' => $miner->payouts()->sum('amount'),
		]);
	}

	public function exportAddressPayoutsGraph(CheckBalance $request)
	{
		$miner = $this->getMiner($request);
		return $this->exportPayoutsGraph($miner->getDailyPayouts(), 'address', $miner->address);
	}

	public function exportAddressPayoutsListing(CheckBalance $request)
	{
		$miner = $this->getMiner($request);

		if ($miner->payouts()->count() > 10000)
			return $this->exportPayoutsCsv($miner, $miner->payouts()->sum('amount'), 'address', $miner->address);

		return $this->exportPayoutsXlsx($miner->payouts, 'address', $miner->address);
	}

	protected function getMiner(CheckBalance $request)
	{
		$miner = Miner::where('address', $request->input('address'))->first();
		if ($miner)
			return $miner;

		return new Miner([
			'address' => $request->input('address'),
		]);
	}
}
