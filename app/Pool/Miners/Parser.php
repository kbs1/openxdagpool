<?php

namespace App\Pool\Miners;

use App\Pool\BaseParser;

class Parser extends BaseParser
{
	public function getNumberOfMiners()
	{
		$count = 0;

		$this->forEachMinerLine(function($parts) use (&$count) {
			$count++;
		});

		return $count;
	}

	public function getNumberOfActiveMiners()
	{
		$active = 0;

		$this->forEachMinerLine(function($parts) use (&$active) {
			if ($parts[2] === 'active')
				$active++;
		});

		return $active;
	}

	public function getTotalUnpaidShares()
	{
		$total = 0;

		$this->forEachMinerLine(function($parts) use (&$total) {
			//if ($parts[2] === 'active')
				$total += $parts[5];
		});

		return $total;
	}

	public function getMiner($address)
	{
		$miner = null;

		$this->forEachMinerLine(function($parts) use ($address, &$miner) {
			if ($parts[1] === $address) {
				if (!$miner) {
					$miner = new Miner($parts[1], $parts[2], $parts[3], $parts[4], $parts[5]);
				} else {
					$miner->addIpAndPort($parts[3]);
					$miner->addUnpaidShares($parts[5]);

					if ($miner->getStatus() !== 'active' && $parts[2] === 'active')
						$miner->setStatus($parts[2]);
				}
			}
		});

		return $miner;
	}

	public function getMinersByHashrate($pool_hashrate)
	{
		$miners = [];

		$this->forEachMinerLine(function($parts) use (&$miners) {
			if (!isset($miners[$parts[1]])) {
				$miners[$parts[1]] = new Miner($parts[1], $parts[2], $parts[3], $parts[4], $parts[5]);
			} else {
				$miners[$parts[1]]->addIpAndPort($parts[3]);
				$miners[$parts[1]]->addUnpaidShares($parts[5]);

				if ($miners[$parts[1]]->getStatus() !== 'active' && $parts[2] === 'active')
					$miners[$parts[1]]->setStatus($parts[2]);
			}
		});

		foreach ($miners as $address => $miner) {
			if ($miner->getStatus() == 'free') {
				unset($miners[$address]);
				continue;
			}

			$hashrate = 0;
			if ($this->getTotalUnpaidShares() > 0)
				$hashrate = ($miner->getUnpaidShares() / $this->getTotalUnpaidShares()) * $pool_hashrate;

			$miner->setHashrate($hashrate);
		}

		uasort($miners, function ($a, $b) {
			if ($a->getHashrate() == $b->getHashrate())
				return 0;

			return $a->getHashrate() < $b->getHashrate() ? 1 : -1;
		});

		return $miners;
	}

	public function getMinersByIp()
	{
		$miners = [];

		$this->forEachMinerLine(function($parts) use (&$miners) {
			list($ip, $port) = explode(':', $parts[3]);

			if (!isset($miners[$ip]))
				$miners[$ip] = [];

			if (!isset($miners[$ip][$parts[1]])) {
				$miners[$ip][$parts[1]] = new Miner($parts[1], $parts[2], $parts[3], $parts[4], $parts[5]);
			} else {
				$miners[$ip][$parts[1]]->addIpAndPort($parts[3]);
				$miners[$ip][$parts[1]]->addUnpaidShares($parts[5]);

				if ($miners[$ip][$parts[1]]->getStatus() !== 'active' && $parts[2] === 'active')
					$miners[$ip][$parts[1]]->setStatus($parts[2]);
			}
		});

		foreach ($miners as $ip => $list) {
			$miners[$ip]['machines'] = $miners[$ip]['unpaid_shares'] = 0;
			foreach ($list as $address => $miner) {
				if ($miner->getStatus() == 'free') {
					unset($miners[$ip][$address]);
					continue;
				}

				$miners[$ip]['machines'] += $miner->getMachinesCount();
				$miners[$ip]['unpaid_shares'] += $miner->getUnpaidShares();
			}

			if (count($miners[$ip]) == 2)
				unset($miners[$ip]);
		}

		uasort($miners, function ($a, $b) {
			if ($a['machines'] == $b['machines'])
				return 0;

			return $a['machines'] < $b['machines'] ? 1 : -1;
		});

		return $miners;
	}

	protected function forEachMinerLine(callable $callback, $skip = 0)
	{
		$this->forEachLine(function($line) use ($callback) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 6)
				return;

			if ($parts[0] === '-1.')
				return;

			$callback($parts);
		}, $skip);
	}
}
