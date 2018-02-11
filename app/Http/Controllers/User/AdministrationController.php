<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Users\User;
use App\Pool\Formatter;
use App\Http\Requests\{UpdateUser, SaveSettings};
use Setting;

class AdministrationController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('active');
		$this->middleware('admin');
		view()->share('activeTab', 'admin');
	}

	public function users(Formatter $format)
	{
		return view('user.admin.users', [
			'format' => $format,
			'users' => User::orderBy('active')->orderBy('administrator', 'desc')->orderBy('nick')->orderBy('id')->paginate(25),
			'section' => 'users',
		]);
	}

	public function editUser($id)
	{
		if (!($user = User::find($id)))
			return redirect()->back()->with('error', 'User not found.');

		return view('user.admin.edit-user', [
			'user' => $user,
			'section' => 'users',
		]);
	}

	public function updateUser(UpdateUser $request, $id)
	{
		$user = User::findOrFail($id);

		$user->nick = $request->input('nick');
		$user->email = $request->input('email');
		$user->anonymous_profile = (boolean) $request->input('anonymous_profile');
		$user->exclude_from_leaderboard = (boolean) $request->input('exclude_from_leaderboard');
		$user->active = (boolean) $request->input('active');
		$user->administrator = (boolean) $request->input('administrator');

		if ($request->input('password'))
			$user->password = bcrypt($request->input('password'));

		$user->save();

		return redirect()->route('user.admin.users')->with('success', 'User successfully updated.');
	}

	public function poolSettings()
	{
		return view('user.admin.settings', [
			'section' => 'settings',
		]);
	}

	public function savePoolSettings(SaveSettings $request)
	{
		Setting::set('fees_percent', $request->input('fees_percent'));
		Setting::set('reward_percent', $request->input('reward_percent'));
		Setting::set('direct_percent', $request->input('direct_percent'));
		Setting::set('fund_percent', $request->input('fund_percent'));
		Setting::save();

		return redirect()->back()->with('success', 'Settings successfuly updated.');
	}
}
