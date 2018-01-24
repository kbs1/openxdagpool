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
			'graph_data' => $this->getGraphData(Auth::user()->getDailyPayouts(), $sum),
			'payouts_sum' => $sum,
			'activeTab' => 'payouts',
		]);
	}

	public function userPayoutsListing()
	{
		return view('user.payouts.user-payouts-listing', [
			'payouts' => Auth::user()->getPayouts(),
			'payouts_sum' => Auth::user()->getPayoutsSum(),
			'activeTab' => 'payouts',
		]);
	}

	public function exportUserPayoutsGraph()
	{
		$user = Auth::user();
		return $this->exportPayoutsGraph($user->getDailyPayouts(), 'user', $user->display_nick);
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

		return view('user.payouts.miner-payouts-graph', [
			'miner' => $miner,
			'graph_data' => $this->getGraphData($miner->getDailyPayouts(), $sum),
			'payouts_sum' => $sum,
			'activeTab' => 'miners',
		]);
	}

	public function minerPayoutsListing($uuid)
	{
		if (($miner = Auth::user()->miners()->where('uuid', $uuid)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		return view('user.payouts.miner-payouts-listing', [
			'miner' => $miner,
			'payouts' => $miner->payouts()->paginate(500),
			'payouts_sum' => $miner->payouts->sum('amount'),
			'activeTab' => 'miners',
		]);
	}

	public function exportMinerPayoutsGraph($uuid)
	{
		if (($miner = Auth::user()->miners()->where('uuid', $uuid)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		return $this->exportPayoutsGraph($miner->getDailyPayouts(), 'address', $miner->address);
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
			$export[] = [$payout->made_at->format('Y-m-d H:i:s'), $payout->sender, $payout->recipient, $payout->amount];
			$total += $payout->amount;
		}

		$export[] = ['', '', '', ''];
		$export[] = ['', '', 'Total:', sprintf('%.09f', $total)];

		return Excel::create(config('app.name') . ' - payouts listing for ' . $for_label . ' ' . $for, function($excel) use ($export) {
			$excel->sheet('Payouts listing', function($sheet) use ($export) {
				$sheet->fromArray($export, null, 'A1', false, false);
			});
		})->download('xlsx');
	}

	protected function exportPayoutsGraph($days, $for_label, $for)
	{
		$export = [
			[ucfirst($for_label) . ':', $for],
			['', ''],
			['Date', 'Amount']
		];

		$total = 0;
		foreach ($days as $day) {
			$export[] = [$day->date, sprintf('%.09f', $day->total)];
			$total += $day->total;
		}

		$export[] = ['', ''];
		$export[] = ['Total:', sprintf('%.09f', $total)];

		return Excel::create(config('app.name') . ' - daily payouts for ' . $for_label . ' ' . $for, function($excel) use ($export) {
			$excel->sheet('Daily payouts', function($sheet) use ($export) {
				$sheet->fromArray($export, null, 'A1', false, false);
			});
		})->download('xlsx');
	}

	protected function getGraphData($days, &$sum)
	{
		$graph = ['x' => [], 'Payout' => []];
		$sum = 0;

		foreach ($days as $day) {
			$graph['x'][] = $day->date;
			$graph['Payout'][] = sprintf('%.09f', $day->total);
			$sum += $day->total;
		}

		return json_encode($graph);
	}
}
