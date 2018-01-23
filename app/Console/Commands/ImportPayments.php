<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\{DataReader, BalancesParser};
use App\Pool\Payments\Parser as PaymentsParser;

use App\Miners\Miner;

class SaveMinerStats extends Command
{
	protected $signature = 'payments:import';
	protected $description = 'Imports all new payments for each registered miner.';

	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$payments_parser = new PaymentsParser($this->reader->getPayments());

		foreach (Miner::all() as $miner) {
			$payments = $payments_parser->getPaymentsForRecipient($miner->address);
			if (!$payments) continue;

			$last_payment = $miner->payments()->orderBy('id', 'desc')->first();

			foreach ($payments as $payment) {
				if ($payment->compareDate........
			}

			$miner->payments()->create([
				'unpaid_shares' => $miner->unpaid_shares,
			]);
		}

		$this->info('Completed successfully.');
	}
}
