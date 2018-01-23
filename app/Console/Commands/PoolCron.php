<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PoolCron extends Command
{
	protected $signature = 'pool:cron';
	protected $description = 'Run required cron jobs in succession.';

	public function handle()
	{
		$this->call('payments:import');
		$this->call('stats:miners');
		$this->call('stats:pool');
		$this->call('alerts:miners');
		$this->info('Completed successfully.');
	}
}
