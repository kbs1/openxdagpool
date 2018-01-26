<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Auth;

use App\Http\Requests\UpdateProfile;

class ProfileController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('active');
		view()->share('activeTab', 'profile');
	}

	public function index()
	{
		return view('user.profile');
	}

	public function update(UpdateProfile $request)
	{
		$user = Auth::user();

		$user->nick = $request->input('nick');
		$user->email = $request->input('email');
		$user->anonymous_profile = (boolean) $request->input('anonymous_profile');
		$user->exclude_from_leaderboard = (boolean) $request->input('exclude_from_leaderboard');

		if ($request->input('password'))
			$user->password = bcrypt($request->input('password'));

		$user->save();

		return redirect()->back()->with('success', 'Profile successfully updated.');
	}
}
