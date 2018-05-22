<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Support\{ExclusiveLock, UnableToObtainLockException};

class DownloadPoolData extends Command
{
	protected $signature = 'pool:download-data';
	protected $description = 'Downloads pool miners, pool state, pool stats, found blocks and payouts data into the storage folder using wget or cp.';

	public function handle()
	{
		$this->download(env('DOWNLOAD_MINERS'), env('MINERS'));
		$this->download(env('DOWNLOAD_STATE'), env('STATE'));
		$this->download(env('DOWNLOAD_STATS'), env('STATS'));

		try {
			$lock = new ExclusiveLock('blocks', 0);
			$lock->obtain();
			$this->download(env('DOWNLOAD_BLOCKS'), env('BLOCKS'));
			$lock->release();
		} catch (UnableToObtainLockException $ex) {
			// don't download new blocks data while blocks import is still in progress
		}

		try {
			$lock = new ExclusiveLock('payouts', 0);
			$lock->obtain();
			$this->download(env('DOWNLOAD_PAYOUTS'), env('PAYOUTS'));
			$lock->release();
		} catch (UnableToObtainLockException $ex) {
			// don't download new payouts data while payouts import is still in progress
		}

		$this->info('DownloadPoolData completed successfully.');
	}

	protected function download($source, $destination)
	{
		$source = $this->getPath($source);
		$destination = $this->getPath($destination);

		if (substr($source, 0, 7) !== 'http://' && substr($source, 0, 8) !== 'https://')
			exec(env('CP_EXECUTABLE', '/bin/cp') . ' ' . escapeshellarg($source) . ' ' . escapeshellarg($destination));
		else
			exec(env('WGET_EXECUTABLE', '/usr/bin/wget') . ' -q -O ' . escapeshellarg($destination) . ' ' . escapeshellarg($source));
	}

	protected function getPath($path)
	{
		if (substr($path, 0, 2) === './')
			return base_path($path);

		return $path;
	}
}
