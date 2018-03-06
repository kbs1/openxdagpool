<?php

namespace App\Http\Controllers;

use App\Users\Leaderboard;

class LeaderboardController extends Controller
{
	public function index(Leaderboard $leaderboard)
	{
		return view('leaderboard', [
			'leaderboard' => $leaderboard->get(),
			'activeTab' => 'leaderboard',
		]);
	}
}
