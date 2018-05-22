<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Pool\Formatter;
use App\Miners\Miner;

class MinersController extends Controller
{
	protected $format;

	public function __construct(Formatter $format)
	{
		$this->format = $format;
		$this->middleware('auth');
	}

	public function list(Request $request)
	{
		$user = Auth::user();
		$result = [];
		$uuids = ((array) $request->input('uuid')) ?: ['none'];

		foreach ($user->miners()->whereIn('uuid', $uuids)->get() as $miner) {
			$result[$miner->uuid] = [
				'status' => $miner->status,
				'ip_and_port' => $miner->ip_and_port,
				'average_hashrate' => $this->format->hashrate($miner->average_hashrate),
				'unpaid_shares' => $miner->unpaid_shares,
				'balance' => $this->format->balance($miner->balance),
				'balance_exact' => $this->format->fullBalance($miner->balance),
				'earned' => $this->format->balance($miner->earned),
				'earned_exact' => $this->format->fullBalance($miner->earned),
			];
		}

		return response()->json($result);
	}
}
