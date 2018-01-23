<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Excel;

class PayoutsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('active');
	}

	public function userPayoutsGraph()
	{
		return view('user.payouts.user-payouts-graph', [
			'payouts' => Auth::user()->getPayouts(),
			'activeTab' => 'payouts',
		]);
	}

	public function userPayoutsListing()
	{
		return view('user.payouts.user-payouts-listing', [
			'payouts' => Auth::user()->getPayouts(),
			'activeTab' => 'payouts',
		]);
	}

	public function exportUserPayoutsListing()
	{
		$user = Auth::user();

		return $this->exportPayoutsListing($user->getPayouts(), 'user', $user->display_nick);
	}

	public function minerPayoutsGraph($uuid)
	{
		if (($miner = Auth::user()->miners()->where('uuid', $uuid)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		$payouts = $miner->payouts

		return view('user.payouts.miner-payouts-graph', [
			'miner' => $miner,
			'payouts' => $miner->payouts,
			'activeTab' => 'miners',
		]);
	}

	public function minerPayoutsListing($uuid)
	{
		if (($miner = Auth::user()->miners()->where('uuid', $uuid)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		return view('user.payouts.miner-payouts-listing', [
			'miner' => $miner,
			'payouts' => $miner->payouts,
			'activeTab' => 'miners',
		]);
	}

	public function exportMinerPayoutsListing($uuid)
	{
		if (($miner = Auth::user()->miners()->where('uuid', $uuid)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		return $this->exportPayoutsListing($miner->payouts, 'address', $miner->address);
	}

	protected function exportPayoutsListing($payouts, $for_label, $for)
	{
		$export = [
			[ucfirst($for_label) . ':', $for, '', ''],
			['', '', '', ''],
			['Date and time', 'Sender', 'Recipient', 'Amount']
		];

		$total = 0;
		foreach ($payouts as $payout) {
			$export[] = [$payout->precise_made_at->format('Y-m-d H:i:s.u'), $payout->sender, $payout->recipient, $payout->amount];
			$total += $payout->amount;
		}

		$export[] = ['', '', '', ''];
		$export[] = ['', '', 'Total:', sprintf('%.09f', $total)];

		return Excel::create(config('app.name') . ' - payouts for ' . $for_label . ' ' . $for, function($excel) use ($export) {
			$excel->sheet('Payouts', function($sheet) use ($export) {
				$sheet->fromArray($export, null, 'A1', false, false);
			});
		})->download('xlsx');
	}

	protected function getPayouts

	protected function getGraphData($payouts)
	{
		$graph = ['x' => [], 'Payouts' => []];

		foreach ($stats as $stat) {
			$datetime = $stat->created_at->subMinutes(5)->format('Y-m-d H:i');

			$pool_hashrate['x'][] = $datetime;
			$active_miners['x'][] = $datetime;
			$network_hashrate['x'][] = $datetime;

			$pool_hashrate['Pool hashrate (Gh/s)'][] = $stat->pool_hashrate / 1000000000;
			$network_hashrate['Network hashrate (Gh/s)'][] = $stat->network_hashrate / 1000000000;

			$active_miners['Active pool miners'][] = $stat->active_miners;
		}

		return response()->json([
			'pool_hashrate' => $pool_hashrate,
			'active_miners' => $active_miners,
			'network_hashrate' => $network_hashrate,
		]);
	}
}
