<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\{DataReader, BalancesChecker};
use App\Pool\Miners\Parser as MinersParser;

use App\Miners\Miner;

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
		$miners_parser = new MinersParser($this->reader->getMiners());
		$total_unpaid_shares = (float) $miners_parser->getTotalUnpaidShares();

		Miner::unguard();
		foreach (Miner::all() as $miner) {
			if (($pool_miner = $miners_parser->getMiner($miner->address)) === null) {
				$miner->fill([
					'status' => 'offline',
					'ip_and_port' => null,
					'machines_count' => 0,
					'hashrate' => 0,
					'unpaid_shares' => 0,
					'balance' => (float) $this->balances->getBalance($miner->address),
					'earned' => $miner->payouts()->sum('amount'),
				]);

				$miner->save();

				continue;
			}

			$miner->fill([
				'status' => $pool_miner->getStatus(),
				'ip_and_port' => $pool_miner->getIpsAndPort(),
				'machines_count' => $pool_miner->getMachinesCount(),
				'hashrate' => $miner->getEstimatedHashrate($total_unpaid_shares),
				'unpaid_shares' => $pool_miner->getUnpaidShares(),
				'balance' => (float) $this->balances->getBalance($miner->address),
				'earned' => $miner->payouts()->sum('amount'),
			]);

			$miner->save();

			$miner->unpaidShares()->create([
				'unpaid_shares' => $miner->unpaid_shares,
			]);
		}

		$this->info('SaveMinerStats completed successfully.');
	}
}
