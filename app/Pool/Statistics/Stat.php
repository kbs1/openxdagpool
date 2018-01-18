<?php

namespace App\Pool\Statistics;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
	protected $table = 'stats';
	protected $fillable = ['pool_hashrate', 'network_hashrate', 'active_miners'];
}
