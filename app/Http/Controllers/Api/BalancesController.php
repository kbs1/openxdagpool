<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckBalance;
use App\Miners\Miner;

use App\Pool\BalancesChecker;

class BalancesController extends Controller
{
	public function check(CheckBalance $request, BalancesChecker $balances)
	{
		$address = $request->input('address');

		if ($miner = Miner::where('address', $address)->orderBy('id', 'asc')->first())
			return response()->json(['status' => true, 'message' => "Balance on address \"$address\" is {$miner->balance} XDAG."]);

		$balance = $balances->getBalance($address);

		if ($balance === null)
			return response()->json(['status' => false, 'message' => "Address \"$address\" is not known on the network."]);

		return response()->json(['status' => true, 'message' => "Balance on address \"$address\" is $balance XDAG."]);
	}
}
