<?php

namespace App\Pool;

class BalancesChecker
{
	protected $balances = [];

	public function getBalance($address)
	{
		if (isset($this->balances[$address]))
			return $this->balances[$address];

		$result = @file_get_contents(sprintf(env('BALANCE_CHECKER_URL'), urlencode($address)));

		if ($result && preg_match('/\d+\.\d{9}/siu', $result, $match))
			return $this->balances[$address] = $match[0];

		return null;
	}
}
