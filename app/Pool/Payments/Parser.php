<?php

namespace App\Pool\Payments;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	protected $list = [];

	public function getPayments()
	{
		return $this->list;
	}

	public function getNumberOfPayments()
	{
		return count($this->list);
	}

	public function getTotalPaidAmount()
	{
		$total = 0;
		foreach ($this->list as $payment)
			$total += $payment->getAmount();

		return $total;
	}

	public function getPaymentsForRecipient($address)
	{
		$list = [];

		foreach ($this->list as $payment) {
			if ($payment->getRecipient() === $address)
				$list[] = $payment;
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

			$this->list[] = new Payment($parts[0] . ' ' . $parts[1], substr(substr($parts[2], 1), 0, -1), $parts[6], $parts[8], $parts[10]);
		}
	}
}
