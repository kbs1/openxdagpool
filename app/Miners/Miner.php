<?php

namespace App\Miners;

use Illuminate\Database\Eloquent\Model;

use App\Users\User;

class Miner extends Model
{
	use \App\Support\HasUuid;

	protected $fillable = ['address', 'email_alerts'];

	/* relations */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
