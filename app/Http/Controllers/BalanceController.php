<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckBalance;
use App\Pool\DataReader;
use App\Pool\Balances\Parser as BalanceParser;

class BalanceController extends Controller
{
	public function check(CheckBalance $request, DataReader $reader)
	{
		$address = $request->input('address');
		$balances = new BalanceParser($reader->getBalances());

		if (!$balances->addressExists($address))
			return redirect()->back()->with('warning', "Address \"$address is not known\" on the network.");

		$balance = $balances->getBalance($address);
		return redirect()->back()->with('success', "Balance on address \"$address\" is $balance XDAG.");
	}
}
