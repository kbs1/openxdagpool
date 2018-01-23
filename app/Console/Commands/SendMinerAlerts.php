<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Miners\Miner;
use App\Mail\{MinerWentOffline, MinerBackOnline};

use Illuminate\Support\Facades\Mail;

class SendMinerAlerts extends Command
{
	protected $signature = 'alerts:miners';
	protected $description = 'Check each registered miner and send went-offline or back-online notification whenever applicable';

	public function handle()
	{
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
