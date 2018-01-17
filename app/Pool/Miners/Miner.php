<?php

namespace App\Pool\Miners;

class Miner
{
	protected $address, $status, $ips = [], $bytes, $nopaid_shares;
	protected $list = [];

	public function __construct($address, $status, $ip, $bytes, $nopaid_shares)
	{
		$this->address = $address;
		$this->status = $status;
		$this->ips = [$ip];
		$this->bytes = $bytes;
		$this->nopaid_shares = $nopaid_shares;
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

	public function getNopaidShares()
	{
		return $this->nopaid_shares;
	}

	public function addIpAndPort($ip)
	{
		$this->ips[] = $ip;
	}

	public function addNopaidShares($amount)
	{
		$this->nopaid_shares += $amount;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
}
