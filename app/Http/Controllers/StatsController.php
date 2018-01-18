<?php

namespace App\Http\Controllers;

class StatsController extends Controller
{
	public function index()
	{
		return view('stats.index', [
			'activeTab' => 'stats',
		]);
	}
}
