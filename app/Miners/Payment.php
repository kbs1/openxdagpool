<?php

namespace App\Miners;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $table = 'miner_payments';
	protected $dates = ['created_at', 'updated_at', 'made_at'];
	protected $fillable = ['made_at', 'made_at_milliseconds', 'tag', 'sender', 'amount'];

	/* relations */
	public function miner()
	{
		return $this->belongsTo(Miner::class);
	}

	/* attributes */
	public function getMadeAtFullAttribute()
	{
		return $this->made_at->format('Y-m-d H:i:s') . '.' . sprintf('%03d', $this->made_at_milliseconds);
	}
}
