<?php

namespace App\Http\Controllers;

use App\Users\User;
use App\Pool\Formatter;

class LeaderboardController extends Controller
{
	public function index(Formatter $format)
	{
		$leaderboard = [];

		foreach (User::where('exclude_from_leaderboard', false)->with('miners')->get() as $user)
			$leaderboard[] = [
				'hashrate' => $format->hashrate($hashrate = $user->miners->sum('hashrate')),
				'hashrate_exact' => $hashrate,
				'user' => $user
			];

		usort($leaderboard, function ($a, $b) {
			if ($a['hashrate_exact'] == $b['hashrate_exact'])
				return ($a['user']->id < $b['user']->id) ? -1 : 1;

			return ($a['hashrate_exact'] < $b['hashrate_exact']) ? 1 : -1;
		});

		return view('leaderboard', [
			'leaderboard' => array_values($leaderboard),
			'activeTab' => 'leaderboard',
		]);
	}
}
