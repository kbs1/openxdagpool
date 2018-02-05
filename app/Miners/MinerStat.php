<?php

namespace App\Miners;

use Illuminate\Database\Eloquent\Model;

class MinerStat extends Model
{
	protected $table = 'miner_stats';
	protected $fillable = ['miner_id', 'unpaid_shares', 'hashrate'];
}
