<?php

namespace App\Miners;

use Illuminate\Database\Eloquent\Model;

use App\Users\User;
use Carbon\Carbon;

class Miner extends Model
{
	use \App\Support\HasUuid;

	protected $fillable = ['address', 'note', 'email_alerts'];

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
		$sum = $count = $last = 0;
		$shares = $this->unpaidShares()->where('created_at', '>', Carbon::now()->subHours(24))->orderBy('id', 'asc')->get();

		foreach ($shares as $share) {
			$diff = $share->unpaid_shares - $last;

			if ($diff >= 0) {
				$sum += $diff;
				$count++;
			}

			$last = $share->unpaid_shares;
		}

		return $count ? $sum / $count : 0;

		// OLD WAY (less precise):
		// return $this->unpaidShares()->selectRaw('miner_id, avg(unpaid_shares) average')->where('created_at', '>', Carbon::now()->subMinutes(30))->groupBy('miner_id')->pluck('average')->first();
	}
}
