<?php

namespace App\Pool\Statistics;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
	protected $table = 'stats';
	protected $fillable = ['pool_hashrate', 'total_unpaid_shares', 'network_hashrate', 'active_miners'];
}
