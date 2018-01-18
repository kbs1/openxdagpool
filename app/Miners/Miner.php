<?php

namespace App\Miners;

use Illuminate\Database\Eloquent\Model;

use App\Users\User;
use Carbon\Carbon;

class Miner extends Model
{
	use \App\Support\HasUuid;

	protected $fillable = ['address', 'email_alerts'];

	/* relations */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function unpaidShares()
	{
		return $this->hasMany(UnpaidShare::class);
	}

	/* methods */
	public function getAverageUnpaidSharesAttribute()
	{
		return $this->unpaidShares()->selectRaw('miner_id, avg(unpaid_shares) average')->where('created_at', '>', Carbon::now()->subMinutes(30))->groupBy('miner_id')->pluck('average')->first();
	}
}
