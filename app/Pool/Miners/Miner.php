<?php

namespace App\Pool\Miners;

class Miner
{
	protected $address, $status, $ip, $bytes, $nopaid_shares;
	protected $list = [];

	public function __construct($address, $status, $ip, $bytes, $nopaid_shares)
	{
		$this->address = $address;
		$this->status = $status;
		$this->ip = $ip;
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

	public function getIpAndPort()
	{
		return $this->ip;
	}

	public function getBytesInOut()
	{
		return $this->bytes;
	}

	public function getNopaidShares()
	{
		return $this->nopaid_shares;
	}
}
