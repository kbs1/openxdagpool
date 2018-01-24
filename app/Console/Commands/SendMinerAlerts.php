<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Miners\Miner;
use App\Mail\{MinerWentOffline, MinerBackOnline};

use App\Pool\DataReader;
use App\Pool\Statistics\Parser as StatisticsParser;

use Illuminate\Support\Facades\Mail;

class SendMinerAlerts extends Command
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
		$stats_parser = new StatisticsParser($this->reader->getStatistics());
		$pool_hashrate = (float) $stats_parser->getPoolHashrate();

		if ($pool_hashrate == 0) {
			$this->line('Zero pool hashrate, not sending alerts.');
			$this->info('SendMinerAlerts completed successfully.');
			return;
		}

		foreach (Miner::where('email_alerts', true)->get() as $miner) {
			if ($miner->status === 'offline' && $miner->seen_online) {
				$this->line("Sending 'went offline' notification for miner '{$miner->address}' to '{$miner->user->email}'...");
				$miner->seen_online = false;
				$miner->save();

				Mail::to($miner->user->email, $miner->user->nick)->send(new MinerWentOffline($miner));
			} else if ($miner->status !== 'offline' && !$miner->seen_online) {
				$this->line("Sending 'back online' notification for miner '{$miner->address}' to '{$miner->user->email}'...");
				$miner->seen_online = true;
				$miner->save();

				Mail::to($miner->user->email, $miner->user->nick)->send(new MinerBackOnline($miner));
			}
		}

		$this->info('SendMinerAlerts completed successfully.');
	}
}
