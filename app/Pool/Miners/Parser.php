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
					$miner->addInOutBytes($parts[4]);
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
			if ($parts[1] == 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA')
				return;

			if (!isset($miners[$parts[1]])) {
				$miners[$parts[1]] = new Miner($parts[1], $parts[2], $parts[3], $parts[4], $parts[5]);
			} else {
				$miners[$parts[1]]->addIpAndPort($parts[3]);
				$miners[$parts[1]]->addInOutBytes($parts[4]);
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
			if ($parts[1] == 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA')
				return;

			list($ip, $port) = explode(':', $parts[3]);

			if ($ip === '0.0.0.0' && $port === '0')
				return;

			if (!isset($miners[$ip]))
				$miners[$ip] = [];

			if (!isset($miners[$ip][$parts[1]])) {
				$miners[$ip][$parts[1]] = new Miner($parts[1], $parts[2], $parts[3], $parts[4], $parts[5]);
			} else {
				$miners[$ip][$parts[1]]->addIpAndPort($parts[3]);
				$miners[$ip][$parts[1]]->addInOutBytes($parts[4]);
				$miners[$ip][$parts[1]]->addUnpaidShares($parts[5]);

				if ($miners[$ip][$parts[1]]->getStatus() !== 'active' && $parts[2] === 'active')
					$miners[$ip][$parts[1]]->setStatus($parts[2]);
			}
		});

		foreach ($miners as $ip => $list) {
			$miners[$ip]['machines'] = $miners[$ip]['unpaid_shares'] = 0;
			$miners[$ip]['in_out_bytes'] = '0/0';
			foreach ($list as $address => $miner) {
				if ($miner->getStatus() == 'free') {
					unset($miners[$ip][$address]);
					continue;
				}

				$miners[$ip]['machines'] += $miner->getMachinesCount();
				$miners[$ip]['unpaid_shares'] += $miner->getUnpaidShares();

				$bytes = explode('/', $miners[$ip]['in_out_bytes']);
				$miner_bytes = explode('/', $miner->getInOutBytes());
				$miners[$ip]['in_out_bytes'] = ($bytes[0] + $miner_bytes[0]) . '/' . ($bytes[1] + $miner_bytes[1]);
			}

			if (count($miners[$ip]) == 3)
				unset($miners[$ip]);
		}

		uasort($miners, function ($a, $b) {
			if ($a['machines'] == $b['machines'])
				return 0;

			return $a['machines'] < $b['machines'] ? 1 : -1;
		});

		return $miners;
	}

	// this function is backwards compatible with pool versions <= 0.2.1
	protected function forEachMinerLine(callable $callback, $skip = 0)
	{
		$last_miner = null;

		$this->forEachLine(function($line) use ($callback, &$last_miner) {
			$parts = preg_split('/\s+/siu', $line);

			if (count($parts) !== 6)
				return;

			if ($parts[0] === '-1.')
				return;

			if ($last_miner && $parts[0][0] === 'C') {
				$parts[1] = $last_miner[1]; // replace miner's address from last active miner entry
				$parts[2] = $last_miner[2]; // replace miner's state from last active miner entry
				$parts[5] = $last_miner[5]; // replace miner's unpaid shares with value from last active miner entry
				$last_miner[5] = 0; // replace unpaid shares only for first connection, treat all other connections as zero unpaid shares (sum => vallue from last active miner entry)
			} else {
				$last_miner = $parts; // store currently processed miner entry
			}

			// in new miners output, IP and IN/OUT information is lost when miner disconnects. Replace with placeholder values.
			if ($parts[2] !== 'active' && $parts[3] === '-') {
				$parts[3] = '0.0.0.0:0';
				$parts[4] = '0/0';
			}

			// in new miners output, skip the first "active" miner line, and use only "C" lines - miner's connections
			// this check will succeed only for active miners in new output - we don't replace IP and IN/OUT bytes
			// in the condition above for miners in 'active' state
			if ($parts[3] !== '-')
				$callback($parts);
		}, $skip);
	}
}
