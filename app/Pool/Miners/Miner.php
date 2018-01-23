<?php

namespace App\Pool\Miners;

class Miner
{
	protected $address, $status, $ips = [], $bytes, $unpaid_shares;

	public function __construct($address, $status, $ip, $bytes, $unpaid_shares)
	{
		$this->address = $address;
		$this->status = $status;
		$this->ips = [$ip];
		$this->bytes = $bytes;
		$this->unpaid_shares = $unpaid_shares;
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

	public function getBytesInOut()
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

	public function addIpAndPort($ip)
	{
		$this->ips[] = $ip;
	}

	public function addUnpaidShares($amount)
	{
		$this->unpaid_shares += $amount;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
}
