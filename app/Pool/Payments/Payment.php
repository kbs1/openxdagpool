<?php

namespace App\Pool\Payments;

class Payment
{
	protected $timestamp, $tag, $sender, $receiver, $amount;

	public function __construct($timestamp, $tag, $sender, $receiver, $amount)
	{
		$this->timestamp = $timestamp;
		$this->tag = $tag;
		$this->sender = $sender;
		$this->receiver = $receiver;
		$this->amount = $amount;
	}

	public function getTimestamp()
	{
		return $this->timestamp;
	}

	public function getTag()
	{
		return $this->tag;
	}

	public function getSender()
	{
		return $this->sender;
	}

	public function getReceiver()
	{
		return $this->receiver;
	}

	public function getAmount()
	{
		return $this->amount;
	}
}
