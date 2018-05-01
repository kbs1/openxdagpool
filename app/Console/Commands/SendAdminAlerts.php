<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use Setting, Carbon\Carbon;
use App\Mail\UserMessage;
use App\Miners\ReferenceHashrate;
use App\Users\User;

use App\Pool\DataReader;
use App\Pool\State\Parser as StateParser;
use App\Pool\Statistics\Parser as StatisticsParser;
use App\Pool\Miners\Parser as MinersParser;

class SendAdminAlerts extends Command
{
	protected $signature = 'alerts:pool';
	protected $description = 'Send zero hashrate, abnormal state and reference miner offline admin notifications.';

	protected $reader, $users;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$this->users = User::where('active', true)->where('administrator', true)->get();

		if (!count($this->users)) {
			$this->line('No active admin users, not sending admin alerts.');
			$this->info('SendAdminAlerts completed successfully.');
			return;
		}

		// zero pool hashrate notification
		$stats_parser = new StatisticsParser($this->reader->getStatistics());

		if ($this->canSendNotification('zero_hashrate') && ((float) $stats_parser->getPoolHashrate()) == 0) {
			$this->sendNotification('zero_hashrate', 'Zero pool hashrate - pool down?', 'there is zero hashrate on our pool, either no one is mining at our pool or the pool crashed. Please check pool\'s status.');
			$this->info('SendAdminAlerts completed successfully.');
			return; // do not send any other notifications when pool is down
		} else if (!$this->canSendNotification('zero_hashrate') && ((float) $stats_parser->getPoolHashrate()) > 0) {
			$this->resetLastNotificationDate('zero_hashrate');
		}

		// abnormal pool daemon state notification
		$state_parser = new StateParser($this->reader->getState());

		if ($this->canSendNotification('pool_state') && !$state_parser->isNormalPoolState()) {
			$this->sendNotification('pool_state', 'Abnormal pool daemon state', 'pool daemon is currently in state "' . $state_parser->getPoolState() . '". Outside normal operation, some OpenXDAGPool services might not work correctly. Please check the pool daemon.');
		} else if (!$this->canSendNotification('pool_state') && $state_parser->isNormalPoolState()) {
			$this->resetLastNotificationDate('pool_state');
		}

		// reference miner offline notification
		$reference = new ReferenceHashrate();

		if ($reference->shouldBeUsed()) {
			$miners_parser = new MinersParser($this->reader->getMiners());
			$pool_miner = $miners_parser->getMiner($miner_address = Setting::get('reference_miner_address'));
			$is_offline = !$pool_miner || $pool_miner->getStatus() !== 'active';

			if ($this->canSendNotification('reference_miner_offline') && $is_offline) {
				$this->sendNotification('reference_miner_offline', 'Reference miner offline', 'pool reference miner "' . $miner_address . '" is offline, please check it\'s status.');
			} else if (!$this->canSendNotification('reference_miner_offline') && !$is_offline) {
				$this->resetLastNotificationDate('reference_miner_offline');
			}
		} else {
			$this->resetLastNotificationDate('reference_miner_offline');
		}

		$this->info('SendAdminAlerts completed successfully.');
	}

	protected function canSendNotification($name)
	{
		$last_date = $this->getLastNotificationDate($name);
		return $last_date < Carbon::now()->subDays(3);
	}

	protected function sendNotification($name, $subject, $message)
	{
		foreach ($this->users as $user) {
			$this->line("Sending '$subject' notification to admin email '{$user->email}'...");
			Mail::to($user->email, $user->nick)->send(new UserMessage($user, $subject, $message));
		}

		$this->setLastNotificationDate($name, Carbon::now());
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

	protected function resetLastNotificationDate($name)
	{
		Setting::forget("alert_{$name}_sent_at");
		return Setting::save();
	}
}
