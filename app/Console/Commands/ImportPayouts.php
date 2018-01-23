<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Payouts\Parser as PayoutsParser;
use App\Payouts\Payout;

use Carbon\Carbon;

class ImportPayouts extends Command
{
	protected $signature = 'payouts:import';
	protected $description = 'Imports all new payouts for each registered miner.';

	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$payouts_parser = new PayoutsParser($this->reader->getPayouts());

		$latest = Payout::orderBy('id', 'desc')->first();
		$latest_made_at = $latest ? $latest->precise_made_at : null;

		foreach ($payouts_parser->getPayouts() as $pool_payout) {
			$made_at = $pool_payout->getMadeAt();

			if ($latest_made_at && $made_at <= $latest_made_at && $made_at->micro <= $latest_made_at->micro) // overcome Carbon bug
				continue;

			$payout = new Payout([
				'tag' => $pool_payout->getTag(),
				'sender' => $pool_payout->getSender(),
				'recipient' => $pool_payout->getRecipient(),
				'amount' => $pool_payout->getAmount(),
			]);

			$payout->precise_made_at = $made_at;
			$payout->save();
		}

		$this->info('ImportPayouts completed successfully.');
	}
}
