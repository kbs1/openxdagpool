<?php

namespace App\Miners;

use Illuminate\Database\Eloquent\Model;

use App\Users\User;
use App\Pool\Statistics\Stat as PoolStat;
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

	/* attributes */
	public function getShortAddressAttribute()
	{
		return substr($this->address, 0, 3) . '...' . substr($this->address, -3);
	}

	public function getShortNoteAttribute()
	{
		return str_limit($this->note, 10);
	}

	/* methods */
	public function getAverageUnpaidSharesAttribute()
	{
		return $this->unpaidShares()->selectRaw('miner_id, avg(unpaid_shares) average')->where('created_at', '>', Carbon::now()->subHours(6))->groupBy('miner_id')->pluck('average')->first();

		/*$sum = $count = $last = 0;
		$shares = $this->unpaidShares()->where('created_at', '>', Carbon::now()->subHours(24))->orderBy('id', 'asc')->get();

		foreach ($shares as $share) {
			$diff = $share->unpaid_shares - $last;

			if ($diff >= 0) {
				$sum += $diff;
				$count++;
			}

			$last = $share->unpaid_shares;
		}

		return $count ? $sum / $count : 0;*/
	}

	public function getEstimatedHashrate($total_unpaid_shares)
	{
		$hashrate = 0;
		if ($total_unpaid_shares > 0) {
			$unpaid_proportion = $this->average_unpaid_shares / $total_unpaid_shares;
			if (!is_nan($unpaid_proportion) && !is_infinite($unpaid_proportion)) {
				$hashrate = $unpaid_proportion * PoolStat::selectRaw('avg(pool_hashrate) avg_pool_hashrate')->where('created_at', '>', Carbon::now()->subHours(6))->pluck('avg_pool_hashrate')->first();
			}
		}

		return $hashrate;
	}
}
