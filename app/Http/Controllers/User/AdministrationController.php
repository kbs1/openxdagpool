<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Users\User;
use App\Pool\Formatter;
use App\Http\Requests\{UpdateUser, SaveSettings, SendMassEmail};
use App\Mail\UserMessage;

use Illuminate\Support\Facades\Mail;
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

		Setting::set('pool_name', $request->input('pool_name'));
		Setting::set('pool_tagline', $request->input('pool_tagline'));
		Setting::set('pool_tooltip', $request->input('pool_tooltip'));

		Setting::set('pool_domain', $request->input('pool_domain'));
		Setting::set('pool_port', $request->input('pool_port'));
		Setting::set('website_domain', $request->input('website_domain'));

		Setting::set('contact_email', $request->input('contact_email'));
		Setting::set('important_message_html', $request->input('important_message_html'));
		Setting::set('important_message_until', $request->input('important_message_until'));
		Setting::set('pool_news_html', $request->input('pool_news_html'));

		Setting::save();

		return redirect()->back()->with('success', 'Settings successfuly updated.');
	}

	public function massEmail()
	{
		return view('user.admin.mass-email', [
			'section' => 'mass-email',
		]);
	}

	public function sendMassEmail(SendMassEmail $request)
	{
		if ($request->input('active'))
			$users = User::where('active', true)->whereHas('miners', function ($query) {
				$query->where('status', 'active');
			})->get();
		else
			$users = User::where('active', true)->get();


		foreach ($users as $user)
			Mail::to($user->email, $user->nick)->send(new UserMessage($user, $request->input('subject'), $request->input('content')));

		return redirect()->back()->with('success', 'E-mail successfully sent to ' . count($users) . ' users.');
	}
}
