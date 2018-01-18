<?php

namespace App\Miners;

use Illuminate\Database\Eloquent\Model;

class UnpaidShare extends Model
{
	protected $table = 'miner_unpaid_shares';
	protected $fillable = ['miner_id', 'unpaid_shares'];
}
