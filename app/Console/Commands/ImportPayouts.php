<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Payouts\{Parser as PayoutsParser, Payout as PoolPayout};
use App\Payouts\Payout;
use App\Support\{ExclusiveLock, UnableToObtainLockException};

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
		$lock = new ExclusiveLock('payouts', 100);

		try {
			$lock->obtain();
		} catch (UnableToObtainLockException $ex) {
			$this->line('Unable to obtain payouts lock, exiting.');
			return;
		}

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

		// delete fully imported payouts with zero amount
		// we could not import payouts with zero amount instead of deleting them later,
		// but previous versions imported all payouts, so this way we will keep every installation
		// clean without manual intervention
		\DB::table('payouts')->where('date_fully_imported', true)->where('amount', 0)->delete();

		$this->info('ImportPayouts completed successfully.');
		$lock->release();
	}
}
