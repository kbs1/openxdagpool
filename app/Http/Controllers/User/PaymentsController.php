<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Excel;

class PaymentsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('active');
		view()->share('activeTab', 'miners');
	}

	public function index($address)
	{
		if (($miner = Auth::user()->miners()->where('address', $address)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		return view('user.payments', [
			'miner' => $miner,
		]);
	}

	public function export($address)
	{
		if (($miner = Auth::user()->miners()->where('address', $address)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		$export = [
			['Recipient:', $miner->address, ''],
			['', '', ''],
			['Date and time', 'Sender', 'Amount']
		];

		$total = 0;
		foreach ($miner->payments as $payment) {
			$export[] = [$payment->made_at_full, $payment->sender, $payment->amount];
			$total += $payment->amount;
		}

		$export[] = ['', '', ''];
		$export[] = ['', 'Total:', sprintf('%.09f', $total)];

		return Excel::create(config('app.name') . ' - payments for ' . $miner->address, function($excel) use ($data) {
			$excel->sheet('Payments', function($sheet) use ($data) {
				$sheet->fromArray($data, null, 'A1', false, false);
			});
		})->download('xlsx');

	}
}
