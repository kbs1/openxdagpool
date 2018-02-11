<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class UpdateProfile extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'nick' => ['required', 'string', 'min:3', 'max:20', Rule::unique('users')->ignore(Auth::user()->id)],
			'email' => ['required',	'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::user()->id)],
			'password' => 'nullable|confirmed|min:6',
			'anonymous_profile' => 'required|in:0,1',
			'exclude_from_leaderboard' => 'required|in:0,1',
		];
	}
}
