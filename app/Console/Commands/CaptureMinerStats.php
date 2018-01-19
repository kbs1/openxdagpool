<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Miners\Parser as MinersParser;

use App\Miners\{Miner, UnpaidShare};
use Carbon\Carbon;

class CaptureMinerStats extends Command
{
	protected $signature = 'stats:miners';
	protected $description = 'Capture all registered miner stats if latest stat per miner is older than 4 minutes, 55 seconds';

	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$miners = new MinersParser($this->reader->getMiners());

		foreach (Miner::all() as $miner) {
			$pool_miner = $miners->getMiner($miner->address);

			if (!$pool_miner)
				continue;

			$latest = $miner->unpaidShares()->orderBy('id', 'desc')->first();

			if ($latest && $latest->created_at > Carbon::now()->subMinutes(4)->subSeconds(55))
				continue;

			$unpaid_share = new UnpaidShare([
				'miner_id' => $miner->id,
				'unpaid_shares' => $pool_miner->getUnpaidShares(),
			]);

			$unpaid_share->save();
		}

		$this->info('Completed successfully.');
	}
}
