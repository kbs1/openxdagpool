<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMiner;

use App\Pool\DataReader;
use App\Pool\Miners\Parser as MinersParser;

use App\Miners\Miner;
use Auth;

class MinersController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('active');
		view()->share('activeTab', 'miners');
	}

	public function index()
	{
		return view('user.miners');
	}

	public function create(CreateMiner $request)
	{
		$user = Auth::user();
		$miner = new Miner([
			'address' => $request->input('address'),
			'note' => $request->input('note'),
			'email_alerts' => false,
			'seen_online' => false,
		]);

		$miner->user_id = $user->id;
		$miner->save();

		return redirect()->back()->with('success', 'Miner successfully added.');
	}

	public function delete(CreateMiner $request)
	{
		$user = Auth::user();
		$miner = Miner::where('address', $request->input('address'))->where('user_id', $user->id)->first();

		if (!$miner)
			return redirect()->back()->with('error', 'Miner not found.');

		$miner->unpaidShares()->delete();
		$miner->delete();

		return redirect()->back()->with('success', 'Miner successfully deleted.');
	}

	public function alerts(Request $request, DataReader $reader)
	{
		$user = Auth::user();
		$miners_parser = null;

		foreach ((array) $request->input('alerts') as $uuid => $alert) {
			$miner = Miner::where('uuid', $uuid)->where('user_id', $user->id)->first();

			if (!$miner) continue;

			$miner->email_alerts = $alert ? true : false;

			if ($alert) {
				$miners_parser = $miners_parser ?? new MinersParser($reader->getMiners());
				$miner->seen_online = $miners_parser->getMiner($miner->address) ? true : false;
			}

			$miner->save();
		}

		return redirect()->back()->with('success', 'Preferences updated successfully.');
	}
}
