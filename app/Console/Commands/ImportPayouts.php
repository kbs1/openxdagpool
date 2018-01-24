<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Payouts\{Parser as PayoutsParser, Payout as PoolPayout};
use App\Payouts\Payout;

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

		$latest = Payout::where('date_fully_imported', true)->orderBy('id', 'desc')->first();
		$latest_fully_imported_at = $latest ? $latest->precise_made_at : null;
		$last_made_at = null;

		$payouts_parser->forEachPayoutLine(function(PoolPayout $pool_payout) use ($latest, $latest_fully_imported_at, &$last_made_at) {
			$made_at = $pool_payout->getMadeAt();

			if ($latest_fully_imported_at && $made_at <= $latest_fully_imported_at)
				return;

			if ($latest_fully_imported_at && !$last_made_at) {
				Payout::where('made_at', '<', $made_at)->orWhere(function($query) use ($made_at) {
					$query->where('made_at', '=', $made_at)->where('made_at_milliseconds', '<', floor($made_at->micro / 1000));
				})->update([
					'date_fully_imported' => true,
				]);

				Payout::where('made_at', '=', $made_at)->where('made_at_milliseconds', '=', floor($made_at->micro / 1000))->delete();
			}

			$payout = new Payout([
				'tag' => $pool_payout->getTag(),
				'sender' => $pool_payout->getSender(),
				'recipient' => $pool_payout->getRecipient(),
				'amount' => $pool_payout->getAmount(),
				'date_fully_imported' => false,
			]);

			$payout->precise_made_at = $made_at;
			$payout->save();

			$last_made_at = $last_made_at ?? $made_at;

			if ($last_made_at < $made_at) {
				Payout::where('made_at', '<', $made_at)->orWhere(function($query) use ($made_at) {
					$query->where('made_at', '=', $made_at)->where('made_at_milliseconds', '<', floor($made_at->micro / 1000));
				})->update([
					'date_fully_imported' => true,
				]);

				$last_made_at = $made_at;
			}
		});

		$this->info('ImportPayouts completed successfully.');
	}
}
