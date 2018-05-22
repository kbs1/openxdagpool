<?php

namespace App\Users;

use App\Pool\Formatter;
use Auth;

class Leaderboard
{
	protected $format;

	public function __construct(Formatter $format)
	{
		$this->format = $format;
	}

	public function get()
	{
		$leaderboard = [];
		$miners = [];

		$user = Auth::user();
		if ($user && $user->isAdministrator())
			$users = User::with('miners')->orderBy('id', 'desc')->get();
		else
			$users = User::where('active', true)->where('exclude_from_leaderboard', false)->with('miners')->orderBy('id', 'desc')->get();

		foreach ($users as $user) {
			if (!count($user->miners))
				continue;

			$hashrate = 0;
			foreach ($user->miners as $miner) {
				if (in_array($miner->address, $miners))
					continue;

				$miners[] = $miner->address;
				$hashrate += $miner->average_hashrate;
			}

			$leaderboard[] = [
				'hashrate' => $this->format->hashrate($hashrate),
				'hashrate_exact' => $hashrate,
				'user' => $user
			];
		}

		usort($leaderboard, function ($a, $b) {
			if ($a['hashrate_exact'] == $b['hashrate_exact'])
				return ($a['user']->id > $b['user']->id) ? -1 : 1;

			return ($a['hashrate_exact'] < $b['hashrate_exact']) ? 1 : -1;
		});

		return array_values($leaderboard);
	}
}
