<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pool\DataReader;
use App\Pool\Blocks\{Parser as BlocksParser, Block as PoolBlock};
use App\FoundBlocks\FoundBlock;

use Carbon\Carbon;

class ImportFoundBlocks extends Command
{
	protected $signature = 'blocks:import';
	protected $description = 'Imports all new pool found blocks.';

	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
		parent::__construct();
	}

	public function handle()
	{
		$blocks_parser = new BlocksParser($this->reader->getBlocks());

		$latest = FoundBlock::orderBy('id', 'desc')->first();
		$latest_fully_imported_at = $latest ? $latest->precise_found_at : null;
		$last_found_at = null;
		$insert = [];
		$inserted = 0;

		$blocks_parser->forEachBlockLine(function(PoolBlock $pool_block) use ($latest_fully_imported_at, &$last_found_at, &$insert, &$inserted) {
			$found_at = $pool_block->getFoundAt();

			if ($latest_fully_imported_at && $found_at <= $latest_fully_imported_at)
				return;

			if ($last_found_at && $last_found_at == $found_at)
				return; // do not import the same block twice

			$insert[] = [
				'found_at' => $found_at->format('Y-m-d H:i:s'),
				'found_at_milliseconds' => floor($found_at->micro / 1000),
				'tag' => $pool_block->getTag(),
				'hash' => $pool_block->getHash(),
				't' => $pool_block->getT(),
				'res' => $pool_block->getRes(),
				'payout' => $pool_block->getPayout(),
				'fee' => $pool_block->getFee(),
				'created_at' => $now = Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => $now,
			];

			$last_found_at = $found_at;

			$inserted++;

			if ($inserted % 1000 == 0) {
				FoundBlock::insert($insert);
				$this->line("Imported: $inserted");
				$insert = [];
			}
		});

		if ($insert) {
			FoundBlock::insert($insert);
			$this->line("Imported: $inserted");
		}

		$this->info('ImportFoundBlocks completed successfully.');
	}
}
