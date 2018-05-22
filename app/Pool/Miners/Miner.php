<?php

namespace App\Pool\Miners;

use App\Miners\Miner as RegisteredMiner;

class Miner
{
	protected $address, $status, $ips = [], $bytes, $unpaid_shares, $hashrate;

	public function __construct($address, $status, $ip, $bytes, $unpaid_shares, $hashrate = 0)
	{
		$this->address = $address;
		$this->status = $status;
		$this->ips = [$ip];

		$in_out_bytes = explode('/', $bytes);
		$this->bytes = count($in_out_bytes) != 2 ? '0/0' : $bytes;

		$this->unpaid_shares = $unpaid_shares;
		$this->hashrate = $hashrate;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getIpsAndPort()
	{
		return implode(', ', $this->ips);
	}

	public function getInOutBytes()
	{
		return $this->bytes;
	}

	public function getUnpaidShares()
	{
		return $this->unpaid_shares;
	}

	public function getMachinesCount()
	{
		return count($this->ips);
	}

	public function getHashrate()
	{
		return $this->hashrate;
	}

	public function addIpAndPort($ip)
	{
		$this->ips[] = $ip;
	}

	public function addUnpaidShares($amount)
	{
		$this->unpaid_shares += $amount;
	}

	public function addInOutBytes($bytes)
	{
		$bytes = explode('/', $bytes);

		if (count($bytes) != 2)
			return;

		$current_bytes = explode('/', $this->bytes);
		$current_bytes[0] += $bytes[0];
		$current_bytes[1] += $bytes[1];

		$this->bytes = $current_bytes[0] . '/' . $current_bytes[1];
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function setHashrate($hashrate)
	{
		$this->hashrate = $hashrate;
	}

	public function getUsers()
	{
		$miners = RegisteredMiner::with('user')->where('address', $this->getAddress())->get();
		$users = [];

		foreach ($miners as $miner)
			if (!isset($users['x' . $miner->user->nick]))
				$users['x' . $miner->user->nick] = $miner->user;

		return $users;
	}
}
