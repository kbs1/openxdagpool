<?php

namespace App\Pool;

use App\Support\{ExclusiveLock, UnableToObtainLockException};

class BalancesChecker
{
	protected $balances = [];

	public function getBalance($address)
	{
		if (isset($this->balances[$address]))
			return $this->balances[$address];

		if (trim(env('BALANCE_CHECKER_URL')) === '')
			return null;

		$lock = new ExclusiveLock('balances', 300);

		try {
			$lock->obtain();
			$result = @file_get_contents(sprintf(trim(env('BALANCE_CHECKER_URL')), urlencode($address)));
			$lock->release();

			if ($result && preg_match('/\d+\.\d{9}/siu', $result, $match))
				return $this->balances[$address] = $match[0];
		} catch (UnableToObtainLockException $ex) {
			return null;
		}

		return null;
	}
}
