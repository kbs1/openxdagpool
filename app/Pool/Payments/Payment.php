<?php

namespace App\Pool\Payments;

class Payment
{
	protected $timestamp, $tag, $sender, $recipient, $amount;

	public function __construct($timestamp, $tag, $sender, $recipient, $amount)
	{
		$this->timestamp = $timestamp;
		$this->tag = $tag;
		$this->sender = $sender;
		$this->recipient = $recipient;
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

	public function getRecipient()
	{
		return $this->recipient;
	}

	public function getAmount()
	{
		return $this->amount;
	}
}
