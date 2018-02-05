<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\{DataReader, BalancesChecker};
use App\Pool\Miners\Parser as MinersParser;
use App\Pool\Statistics\Stat as PoolStat;

use App\Miners\Miner;
use Carbon\Carbon;

class SaveMinerStats extends Command
{
	protected $signature = 'stats:miners';
	protected $description = 'Updates all status fields for each registered miner and inserts miner\'s unpaid shares.';

	protected $reader, $balances;

	public function __construct(DataReader $reader, BalancesChecker $balances)
	{
		$this->reader = $reader;
		$this->balances = $balances;
		parent::__construct();
	}

	public function handle()
	{
		$stat = PoolStat::orderBy('id', 'desc')->first();
		if (!$stat)
			return;

		$miners_parser = new MinersParser($this->reader->getMiners());

		Miner::unguard();
		foreach (Miner::all() as $miner) {
			$balance = $miner->balance;

			if (!$miner->balance_updated_at || $miner->balance_updated_at <= Carbon::now()->subMinutes(30)) {
				$balance = (float) $this->balances->getBalance($miner->address);
				$miner->balance_updated_at = Carbon::now();
			}

			if (($pool_miner = $miners_parser->getMiner($miner->address)) === null) {
				$miner->fill([
					'status' => 'offline',
					'ip_and_port' => null,
					'machines_count' => 0,
					'hashrate' => 0,
					'unpaid_shares' => 0,
					'balance' => $balance,
					'earned' => $miner->payouts()->sum('amount'),
				]);

				$miner->save();

				continue;
			}

			try {
				$miner->unpaidShares()->create([
					'unpaid_shares' => $pool_miner->getUnpaidShares(),
				]);
			} catch (\Illuminate\Database\QueryException $ex) {
				// the miner might have been deleted just now in web UI, silence the exception and continue with the loop
			}

			$miner->fill([
				'status' => $pool_miner->getStatus(),
				'ip_and_port' => $pool_miner->getIpsAndPort(),
				'machines_count' => $pool_miner->getMachinesCount(),
				'hashrate' => $miner->getEstimatedHashrate($stat),
				'unpaid_shares' => $pool_miner->getUnpaidShares(),
				'balance' => $balance,
				'earned' => $miner->payouts()->sum('amount'),
			]);

			$miner->save();
		}

		$this->info('SaveMinerStats completed successfully.');
	}
}
