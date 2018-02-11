<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;
use App\Users\User;

class UpdateUser extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if ($user = Auth::user())
			return $user->isAdministrator() && $user->isActive();

		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$user = User::findOrFail($this->input('id'));

		$default = [
			'nick' => ['required', 'string', 'min:3', 'max:20', Rule::unique('users')->ignore($user->id)],
			'email' => ['required',	'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
			'password' => 'nullable|confirmed|min:6',
			'anonymous_profile' => 'required|in:0,1',
			'exclude_from_leaderboard' => 'required|in:0,1',
			'active' => 'required|in:0,1',
			'administrator' => 'required|in:0,1',
		];

		$active_admins = User::where('active', true)->where('administrator', true)->count();
		$rules = [];

		if (!$this->input('administrator') && $user->isAdministrator() && $active_admins - 1 <= 0)
			$rules['administrator'] = 'required|in:1';

		if (!$this->input('active') && $user->isActive() && $active_admins - 1 <= 0)
			$rules['active'] = 'required|in:1';

		return $rules + $default;
	}
}
