<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\Statistics\Stat as PoolStat;

use App\Miners\Miner;
use Carbon\Carbon;

class RemoveInactiveMinersHistory extends Command
{
	protected $signature = 'miners:remove-inactive-history';
	protected $description = 'Removes all miner stats for miners that are inactive for more than 3 days.';

	public function handle()
	{
		foreach (Miner::where('status', 'offline')->get() as $miner) {
			if ($miner->stats()->where('created_at', '>=', Carbon::now()->subDays(3))->where('unpaid_shares', '>', 0)->count() == 0)
				$miner->stats()->delete();
		}

		$this->info('RemoveInactiveMinersHistory completed successfully.');
	}
}
