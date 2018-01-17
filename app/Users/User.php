<?php

namespace App\Users;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Miners\Miner;

class User extends Authenticatable
{
	use Notifiable;
	use \App\Support\HasUuid;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nick', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/* relations */
	public function miners()
	{
		return $this->hasMany(Miner::class);
	}

	/* attributes */
	public function getDisplayNickAttribute()
	{
		return '@' . $this->nick;
	}

	/* methods */
	public function isActive()
	{
		return $this->active;
	}

	public function isAdministrator()
	{
		return $this->administrator;
	}
}
