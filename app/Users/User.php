<?php

namespace App\Users;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Miners\{Miner, MinerStat};
use App\Payouts\Payout;

use Carbon\Carbon;
use Auth;

class User extends Authenticatable
{
	use Notifiable;
	use \App\Support\HasUuid;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nick', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/* relations */
	public function miners()
	{
		return $this->hasMany(Miner::class);
	}

	/* attributes */
	public function getDisplayNickAttribute()
	{
		if (!$this->anonymous_profile)
			return $this->nick;

		$user = Auth::user();
		if ($user && ($user->isAdministrator() || $user->id === $this->id))
			return $this->nick;

		return 'anonymous';
	}

	/* methods */
	public function getPayoutsListingNonPaged()
	{
		$addresses = $this->miners->pluck('address');
		return Payout::whereIn('recipient', $addresses ?: ['none'])->orderBy('id', 'asc')->get();
	}

	public function getPayoutsListing($page = null)
	{
		$addresses = $this->miners->pluck('address');
		$query = Payout::whereIn('recipient', $addresses ?: ['none'])->orderBy('id', 'asc');

		if (!$page) {
			$count = clone $query;
			return $query->paginate(500, ['*'], 'page', ceil($count->count('*') / 500));
		}

		return $query->paginate(500);
	}

	public function getPayoutsSum()
	{
		$addresses = $this->miners->pluck('address');
		return Payout::whereIn('recipient', $addresses ?: ['none'])->sum('amount');
	}

	public function getPayoutsCount()
	{
		$addresses = $this->miners->pluck('address');
		return Payout::whereIn('recipient', $addresses ?: ['none'])->count();
	}

	public function getDailyPayouts()
	{
		$addresses = $this->miners->pluck('address');
		return Payout::selectRaw('sum(amount) total, DATE_FORMAT(made_at, "%Y-%m-%d") date')->whereIn('recipient', $addresses ?: ['none'])->groupBy('date')->orderBy('date')->get();
	}

	public function exportPayoutsToCsv($filename)
	{
		$addresses = $this->miners->pluck('address');
		$in_clause = array_fill(0, count($addresses), '?');

		return \DB::statement('SELECT "Date and time" made_at, "Sender" sender, "Recipient" recipient, "Amount" amount
			UNION ALL SELECT CONCAT(made_at, ".", LPAD(made_at_milliseconds, 3, "0")) made_at, sender, recipient, amount FROM payouts WHERE recipient IN (' . implode(', ', $in_clause) . ')
			ORDER BY made_at ASC
			INTO OUTFILE ' . \DB::getPdo()->quote($filename) . ' FIELDS TERMINATED BY "," ENCLOSED BY \'"\' LINES TERMINATED BY "\n"', $addresses->toArray());
	}

	public function getHashrateSum()
	{
		return $this->miners()->sum('hashrate');
	}

	public function getDailyHashrate()
	{
		$miner_ids = $this->miners->pluck('id');
		return MinerStat::selectRaw('sum(hashrate) hashrate, DATE_FORMAT(created_at, "%Y-%m-%d") date')->whereIn('miner_id', $miner_ids ?: [0])->groupBy('date')->orderBy('date')->get();
	}

	public function getLatestHashrate()
	{
		$miner_ids = $this->miners->pluck('id');
		return MinerStat::selectRaw('sum(hashrate) hashrate, DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") date')->whereIn('miner_id', $miner_ids ?: [0])->where('created_at', '>=', Carbon::now()->subDays(3))->groupBy('date')->orderBy('date')->get();
	}

	public function isActive()
	{
		return $this->active;
	}

	public function isAdministrator()
	{
		return $this->administrator;
	}
}
