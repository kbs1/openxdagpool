<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use Setting, Carbon\Carbon;
use App\Mail\UserMessage;
use App\Miners\ReferenceHashrate;
use App\Users\User;

use App\Pool\DataReader;
use App\Pool\Statistics\Parser as StatisticsParser;
use App\Pool\Miners\Parser as MinersParser;

class SendAdminAlerts extends Command
{
	protected $signature = 'alerts:pool';
	protected $description = 'Send pool down notifications and reference miner down notifications if applicable';

	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$users = User::where('active', true)->where('administrator', true)->get();

		if (!count($users)) {
			$this->line('No active admin users, not sending admin alerts.');
			$this->info('SendAdminAlerts completed successfully.');
			return;
		}

		$last_sent_at = $this->getLastNotificationDate('zero_hashrate');
		if ($last_sent_at < Carbon::now()->subDays(3)) {
			$stats_parser = new StatisticsParser($this->reader->getStatistics());
			$pool_hashrate = (float) $stats_parser->getPoolHashrate();

			if ($pool_hashrate == 0) {
				foreach ($users as $user) {
					$this->line("Sending 'zero pool hashrate' notification to admin email '{$user->email}'...");
					Mail::to($user->email, $user->nick)->send(new UserMessage($user, 'Zero pool hashrate - pool down?', 'there is zero hashrate on our pool, either no one is mining at our pool or the pool crashed. Please check pool\'s status.'));
				}

				$this->setLastNotificationDate('zero_hashrate', Carbon::now());
				$this->info('SendAdminAlerts completed successfully.');
				return;
			}
		}

		// check if reference miner went offline
		$reference = new ReferenceHashrate();
		$last_sent_at = $this->getLastNotificationDate('reference_miner_offline');
		if ($reference->shouldBeUsed() && $last_sent_at < Carbon::now()->subDays(3)) {
			$miners_parser = new MinersParser($this->reader->getMiners());
			$pool_miner = $miners_parser->getMiner($miner_address = Setting::get('reference_miner_address'));
			if (!$pool_miner || $pool_miner->getStatus() !== 'active') {
				foreach ($users as $user) {
					$this->line("Sending 'reference miner offline' notification to admin email '{$user->email}'...");
					Mail::to($user->email, $user->nick)->send(new UserMessage($user, 'Reference miner offline', 'pool reference miner "' . $miner_address . '" is offline, please check it\'s status.'));
				}

				$this->setLastNotificationDate('reference_miner_offline', Carbon::now());
			}
		}

		$this->info('SendAdminAlerts completed successfully.');
	}

	protected function getLastNotificationDate($name)
	{
		$last_sent_at = Setting::get("alert_{$name}_sent_at");
		if (!$last_sent_at)
			$last_sent_at = Carbon::now()->subDays(4);
		else
			$last_sent_at = Carbon::createFromFormat('Y-m-d H:i:s', $last_sent_at);

		return $last_sent_at;
	}

	protected function setLastNotificationDate($name, Carbon $date)
	{
		Setting::set("alert_{$name}_sent_at", $date->toDateTimeString());
		return Setting::save();
	}
}
