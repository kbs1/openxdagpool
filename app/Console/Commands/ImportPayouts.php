<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Payouts\{Parser as PayoutsParser, Payout as PoolPayout};
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

		$latest = Payout::where('date_fully_imported', true)->orderBy('id', 'desc')->first();
		$latest_fully_imported_at = $latest ? $latest->precise_made_at : null;
		$last_made_at = null;
		$insert = [];
		$inserted = 0;

		$payouts_parser->forEachPayoutLine(function(PoolPayout $pool_payout) use ($latest_fully_imported_at, &$last_made_at, &$insert, &$inserted) {
			$made_at = $pool_payout->getMadeAt();

			if ($latest_fully_imported_at && $made_at <= $latest_fully_imported_at)
				return;

			if ($latest_fully_imported_at && !$last_made_at)
				Payout::where('made_at', '=', $made_at)->where('made_at_milliseconds', '=', floor($made_at->micro / 1000))->delete();

			$last_made_at = $last_made_at ?? $made_at;

			if ($last_made_at < $made_at) {
				$inserted += count($insert);
				$this->line("Imported: $inserted");

				Payout::insert($insert);
				$insert = [];
				$last_made_at = $made_at;
			}

			$insert[] = [
				'made_at' => $made_at->format('Y-m-d H:i:s'),
				'made_at_milliseconds' => floor($made_at->micro / 1000),
				'tag' => $pool_payout->getTag(),
				'sender' => $pool_payout->getSender(),
				'recipient' => $pool_payout->getRecipient(),
				'amount' => $pool_payout->getAmount(),
				'date_fully_imported' => $latest_fully_imported_at ? false : true,
				'created_at' => $now = Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => $now,
			];
		});

		if ($insert) {
			if ($latest_fully_imported_at) {
				\DB::table('payouts')->where('date_fully_imported', false)->update(['date_fully_imported' => true]);
			} else {
				foreach ($insert as &$ins)
					$ins['date_fully_imported'] = false;

				unset($ins);
			}

			$inserted += count($insert);
			$this->line("Imported: $inserted");

			Payout::insert($insert);
		}

		$this->info('ImportPayouts completed successfully.');
	}
}
