<?php

namespace App\Payouts;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Payout extends Model
{
	protected $fillable = ['made_at', 'made_at_milliseconds', 'tag', 'sender', 'recipient', 'amount'];
	protected $dates = ['made_at', 'created_at', 'updated_at'];

	/* attributes */
	public function getPreciseMadeAtAttribute()
	{
		return Carbon::parse($this->made_at . '.' . $this->made_at_milliseconds * 1000);
	}

	public function getShortSenderAttribute()
	{
		return substr($this->sender, 0, 5) . '...' . substr($this->sender, -5);
	}

	public function getShortRecipientAttribute()
	{
		return substr($this->recipient, 0, 5) . '...' . substr($this->recipient, -5);
	}

	/* setters */
	public function setPreciseMadeAtAttribute(Carbon $value)
	{
		$this->made_at = $value;
		$this->made_at_milliseconds = floor($value->micro / 1000);
	}
}
