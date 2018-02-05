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

		if (!$when || $when->total_unpaid_shares == 0)
			return 0;

		$from = clone $when->created_at;
		$to = clone $when->created_at;
		$from->subHours(6);

		$avg_pool_hashrate = PoolStat::selectRaw('avg(pool_hashrate) avg_pool_hashrate')->where('created_at', '>=', $from)->where('created_at', '<=', $to)->pluck('avg_pool_hashrate')->first();
		$avg_miner_shares = $this->stats()->selectRaw('miner_id, avg(unpaid_shares) average')->where('created_at', '>=', $from)->where('created_at', '<=', $to)->groupBy('miner_id')->pluck('average')->first();
		$proportion = $avg_miner_shares / $when->total_unpaid_shares;

		if (is_nan($proportion) || is_infinite($proportion))
			return 0;

		return $proportion * $avg_pool_hashrate;
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
