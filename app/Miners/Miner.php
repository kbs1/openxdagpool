<?php

namespace App\Miners;

use Illuminate\Database\Eloquent\Model;

use App\Users\User;
use App\Payouts\Payout;
use App\Pool\Statistics\Stat as PoolStat;
use Carbon\Carbon;

class Miner extends Model
{
	use \App\Support\HasUuid;

	protected $fillable = ['address', 'note', 'email_alerts'];
	protected $dates = ['created_at', 'updated_at', 'balance_updated_at'];

	/* relations */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function stats()
	{
		return $this->hasMany(MinerStat::class);
	}

	public function payouts()
	{
		return $this->hasMany(Payout::class, 'recipient', 'address')->orderBy('id', 'asc');
	}

	/* attributes */
	public function getShortAddressAttribute()
	{
		return substr($this->address, 0, 5) . '...' . substr($this->address, -5);
	}

	public function getShortNoteAttribute()
	{
		return str_limit($this->note, 10);
	}

	/* methods */
	public function getEstimatedHashrate(PoolStat $when = null)
	{
		$when = $when ?? PoolStat::orderBy('id', 'desc')->first();

		if (!$when)
			return $miner->hashrate;

		$algo = env('HASHRATE_ALGORITHM', 'realtime');

		if ($algo == 'averaging2')
			return $this->getAveragingHashrate_2($when);
		else if ($algo == 'averaging1')
			return $this->getAveragingHashrate_1($when);

		return $this->getRealtimeHashrate($when);
	}

	// produces results that jump up and down with pool hashrate estimation from the daemon, but usually low hashrate (40% of miner speed or so... based on luck factor)
	protected function getRealtimeHashrate(PoolStat $when)
	{
		if (!$when->total_unpaid_shares)
			return $this->hashrate;

		$from = clone $when->created_at;
		$to = clone $when->created_at;

		$from->subMinutes(4);
		$to->addMinutes(4);

		$stat = $this->stats()->where('created_at', '>=', $from)->where('created_at', '<=', $to)->orderBy('id', 'desc')->first();

		if (!$stat)
			return $this->hashrate;

		$proportion = $stat->unpaid_shares / $when->total_unpaid_shares;

		if (is_nan($proportion) || is_infinite($proportion))
			return $this->hashrate;

		return $proportion * $when->pool_hashrate;
	}

	// produces extremely low hashrates
	/* When miner sends a share, its difficulty converted into nopaid_share value and added to existing nopaid_shares for this miner.
	 * When the payment occurs, it is divided into parts in proportion to nopaid_shares and then nopaid_shares is zeroed (if two payments
	 * come in short interval then nopaid_shares is partially zeroed). I assume that hashrate of miner is in proportion to his average
	 * difficulty of shares. This is not true, but approximately true for a long time period. So you may collect nopaid_shares periodically
	 * and put them into queue, for example day-long queue. When next value comes to queue, the oldest go away. Or, may be better not put
	 * current value of nopaid_shares, but the difference between current and previous, if this difference >= 0. So you always have the
	 * queue for last day. Then just sum its values.
	 */
	protected function getAveragingHashrate_2(PoolStat $when)
	{
		if (!$when->total_unpaid_shares)
			return $this->hashrate;

		$from = clone $when->created_at;
		$to = clone $when->created_at;
		$from->subHours(6);

		$sum = $count = $last = 0;
		$stats = $this->stats()->where('created_at', '>=', $from)->where('created_at', '<=', $to)->orderBy('id', 'asc')->get();

		foreach ($stats as $stat) {
			$diff = $stat->unpaid_shares - $last;

			if ($diff >= 0) { // probably due to this
				$sum += $diff;
				$count++; // and this
			}

			$last = $stat->unpaid_shares; // should do always or only in above if? much lower hashrates if placed in above if only
		}

		$shares = $count ? $sum / $count : 0; // over here
		$proportion = $shares / $when->total_unpaid_shares; // this is also NOW, current pool stat

		if (is_nan($proportion) || is_infinite($proportion))
			return $this->hashrate;

		return $proportion * $when->pool_hashrate;
	}

	// produces low hashrates, multiplied by ~2 gives kind of correct result (why?), but still low for more powerful miners
	protected function getAveragingHashrate_1(PoolStat $when)
	{
		$from = clone $when->created_at;
		$to = clone $when->created_at;
		$from->subHours(1);

		$avg_unpaid_shares = (float) PoolStat::selectRaw('avg(total_unpaid_shares) avg_unpaid_shares')->where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('total_unpaid_shares', '>', 0)->pluck('avg_unpaid_shares')->first();
		if ($avg_unpaid_shares == 0)
			return $this->hashrate;

		$avg_pool_hashrate = (float) PoolStat::selectRaw('avg(pool_hashrate) avg_pool_hashrate')->where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('pool_hashrate', '>', 0)->pluck('avg_pool_hashrate')->first();
		$avg_miner_shares = (float) $this->stats()->selectRaw('miner_id, avg(unpaid_shares) average')->where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('unpaid_shares', '>', 0)->groupBy('miner_id')->pluck('average')->first();

		$proportion = $avg_miner_shares / $avg_unpaid_shares;

		if (is_nan($proportion) || is_infinite($proportion))
			return $this->hashrate;

		return $proportion * $avg_pool_hashrate * 2; // WHY? luck factor?
	}

	public function getPayoutsListing($page = null)
	{
		$query = $this->payouts();

		if (!$page) {
			$count = clone $query;
			return $query->paginate(500, ['*'], 'page', ceil($count->count('*') / 500));
		}

		return $query->paginate(500);
	}

	public function getDailyPayouts()
	{
		return Payout::selectRaw('sum(amount) total, DATE_FORMAT(made_at, "%Y-%m-%d") date')->where('recipient', $this->address)->groupBy('date')->orderBy('date')->get();
	}

	public function exportPayoutsToCsv($filename)
	{
		return \DB::statement('SELECT "_Date and time" made_at, "Sender" sender, "Recipient" recipient, "Amount" amount
			UNION ALL SELECT CONCAT(made_at, ".", LPAD(made_at_milliseconds, 3, "0")) made_at, sender, recipient, amount FROM payouts WHERE recipient = ?
			ORDER BY made_at ASC
			INTO OUTFILE ' . \DB::getPdo()->quote($filename) . ' FIELDS TERMINATED BY "," ENCLOSED BY \'"\' LINES TERMINATED BY "\n"', [$this->address]);
	}

	public function getDailyHashrate()
	{
		return MinerStat::selectRaw('avg(hashrate) hashrate, DATE_FORMAT(created_at, "%Y-%m-%d") date')->where('miner_id', $this->id)->groupBy('date')->orderBy('date')->get();
	}

	public function getLatestHashrate()
	{
		return MinerStat::selectRaw('avg(hashrate) hashrate, DATE_FORMAT(created_at, "%Y-%m-%d %H:00") date')->where('miner_id', $this->id)->where('created_at', '>=', Carbon::now()->subDays(3))->groupBy('date')->orderBy('date')->get();
	}
}
