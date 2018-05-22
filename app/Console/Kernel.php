<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		Commands\DownloadPoolData::class,
		Commands\PoolCron::class,
		Commands\ImportPayouts::class,
		Commands\ImportFoundBlocks::class,
		Commands\SaveMinerStats::class,
		Commands\SavePoolStats::class,
		Commands\SendMinerAlerts::class,
		Commands\SendAdminAlerts::class,
		Commands\RemoveInactiveMinersHistory::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('payouts:import')->cron('45 */3 * * *')->withoutOverlapping();
		$schedule->command('blocks:import')->cron('45 */3 * * *')->withoutOverlapping();
		$schedule->command('pool:cron')->everyFiveMinutes()->withoutOverlapping();
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__.'/Commands');

		require base_path('routes/console.php');
	}
}
