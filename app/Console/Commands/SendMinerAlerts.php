<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Miners\Parser as MinersParser;

use App\Miners\Miner;
use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;

class CaptureMinerStats extends Command
{
	protected $signature = 'alerts:miners';
	protected $description = 'Check each registered miner and send went-offline or back-online notification whenever applicable';

	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$miners = new MinersParser($this->reader->getMiners());

		foreach (Miner::where('email_alerts', true)->get() as $miner) {
			$pool_miner = $miners->getMiner($miner->address);

			if ($pool_miner && !$miner->seen_online) {
				Mail::to($request->user())->send(new OrderShipped($order));
			}

		}

		$this->info('Completed successfully.');
	}
}
