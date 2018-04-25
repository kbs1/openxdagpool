<?php

namespace App\FoundBlocks;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FoundBlock extends Model
{
	protected $fillable = ['found_at', 'found_at_milliseconds', 'tag', 'hash', 't', 'pos', 'payout', 'fee'];
	protected $dates = ['created_at', 'updated_at', 'found_at'];

	/* attributes */
	public function getPreciseFoundAtAttribute()
	{
		return Carbon::parse($this->found_at->format('Y-m-d H:i:s') . '.' . sprintf('%06d', $this->found_at_milliseconds * 1000)); // Carbon doesn't support setting micro directly, we need to call parse again
	}

	public function getShortHashAttribute()
	{
		return substr($this->hash, 0, 16) . '...' . substr($this->hash, -16);
	}

	/* setters */
	public function setPreciseFoundAtAttribute(Carbon $value)
	{
		$this->found_at = $value;
		$this->found_at_milliseconds = floor($value->micro / 1000);
	}
}
