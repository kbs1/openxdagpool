<?php

namespace App\Pool;

class BalancesChecker
{
	protected $balances = [];

	public function getBalance($address)
	{
		if (isset($this->balances[$address]))
			return $this->balances[$address];

		if (trim(env('BALANCE_CHECKER_URL')) === '')
			return null;

		$result = @file_get_contents(sprintf(trim(env('BALANCE_CHECKER_URL')), urlencode($address)));

		if ($result && preg_match('/\d+\.\d{9}/siu', $result, $match))
			return $this->balances[$address] = $match[0];

		return null;
	}
}
