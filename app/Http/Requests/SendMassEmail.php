<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMassEmail extends FormRequest
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
			'active' => 'required|in:0,1',
			'subject' => 'required|max:100',
			'content' => 'required',
		];
	}
}
