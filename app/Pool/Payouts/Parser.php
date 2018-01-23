<?php

namespace App\Pool\Payouts;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	protected $list = [];

	public function getPayouts()
	{
		return $this->list;
	}

	public function getNumberOfPayouts()
	{
		return count($this->list);
	}

	public function getTotalPaidAmount()
	{
		$total = 0;
		foreach ($this->list as $payout)
			$total += $payout->getAmount();

		return $total;
	}

	public function getPayoutsForRecipient($address)
	{
		$list = [];

		foreach ($this->list as $payout) {
			if ($payout->getRecipient() === $address)
				$list[] = $payout;
		}

		return $list;
	}

	protected function parseLines($data)
	{
		$this->lines = explode("\n", $data);
	}

	protected function parse()
	{
		foreach ($this->lines as $line) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 12)
				continue;

			$this->list[] = new Payout($parts[0] . ' ' . $parts[1], substr(substr($parts[2], 1), 0, -1), $parts[6], $parts[8], $parts[10]);
		}
	}
}
