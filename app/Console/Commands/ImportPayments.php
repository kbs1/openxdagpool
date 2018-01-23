<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Payments\Parser as PaymentsParser;
use App\Payments\Payment;

use Carbon\Carbon;

class ImportPayments extends Command
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

		$latest = Payment::orderBy('id', 'desc')->first();
		$latest_made_at = $latest ? $latest->precise_made_at : null;

		foreach ($payments_parser->getPayments() as $pool_payment) {
			$made_at = $pool_payment->getMadeAt();

			if ($latest_made_at && $made_at <= $latest_made_at && $made_at->micro <= $latest_made_at->micro) // overcome Carbon bug
				continue;

			$payment = new Payment([
				'tag' => $pool_payment->getTag(),
				'sender' => $pool_payment->getSender(),
				'recipient' => $pool_payment->getRecipient(),
				'amount' => $pool_payment->getAmount(),
			]);

			$payment->precise_made_at = $made_at;
			$payment->save();
		}

		$this->info('Completed successfully.');
	}
}
