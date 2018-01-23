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
	}

	public function user()
	{
		return view('user.user-payments', [
			'payments' => Auth::user()->getPayments(),
			'activeTab' => 'payments',
		]);
	}

	public function exportUser()
	{
		$user = Auth::user();

		return $this->exportPayments($user->getPayments(), 'user', $user->display_nick);
	}

	public function miner($address)
	{
		if (($miner = Auth::user()->miners()->where('address', $address)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		return view('user.miner-payments', [
			'miner' => $miner,
			'payments' => $miner->payments,
			'activeTab' => 'miners',
		]);
	}

	public function exportMiner($address)
	{
		if (($miner = Auth::user()->miners()->where('address', $address)->first()) === null)
			return redirect()->back()->with('error', 'Miner not found.');

		return $this->exportPayments($miner->payments, 'address', $miner->address);
	}

	protected function exportPayments($payments, $for_label, $for)
	{
		$export = [
			[ucfirst($for_label) . ':', $for, '', ''],
			['', '', '', ''],
			['Date and time', 'Sender', 'Recipient', 'Amount']
		];

		$total = 0;
		foreach ($payments as $payment) {
			$export[] = [$payment->precise_made_at->format('Y-m-d H:i:s.u'), $payment->sender, $payment->recipient, $payment->amount];
			$total += $payment->amount;
		}

		$export[] = ['', '', '', ''];
		$export[] = ['', '', 'Total:', sprintf('%.09f', $total)];

		return Excel::create(config('app.name') . ' - payments for ' . $for_label . ' ' . $for, function($excel) use ($export) {
			$excel->sheet('Payments', function($sheet) use ($export) {
				$sheet->fromArray($export, null, 'A1', false, false);
			});
		})->download('xlsx');
	}
}
