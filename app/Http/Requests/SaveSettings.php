<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSettings extends FormRequest
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
		$total_percent = $this->input('fees_percent') + $this->input('reward_percent') + $this->input('direct_percent') + $this->input('fund_percent');

		return [
			'fees_percent' => 'required|numeric|min:0|max:100' . ($total_percent > 100 ? '|not_in:' . ((float) $this->input('fees_percent')) : ''),
			'reward_percent' => 'required|numeric|min:0|max:100',
			'direct_percent' => 'required|numeric|min:0|max:100',
			'fund_percent' => 'required|numeric|min:0|max:100',

			'pool_name' => 'required',
			'pool_tagline' => 'required',
			'pool_tooltip' => 'required',

			'pool_domain' => 'required',
			'pool_port' => 'required|numeric|min:1|max:65535',
			'website_domain' => 'required',

			'contact_email' => 'required|email',
			'important_message_until' => 'nullable|date',
		];
	}
}
