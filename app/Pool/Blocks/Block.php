<?php

namespace App\Pool\Blocks;

use Carbon\Carbon;

class Block
{
	protected $found_at, $found_at_milliseconds, $tag, $hash, $t, $pos, $payout, $fee;

	public function __construct($found_at, $tag, $hash, $t, $pos, $payout, $fee)
	{
		$found_at = explode('.', $found_at);
		$this->found_at = $found_at[0];
		$this->found_at_milliseconds = intval($found_at[1] ?? 0);
		$this->tag = $tag;
		$this->hash = $hash;
		$this->t = $t;
		$this->pos = $pos;
		$this->payout = $payout;
		$this->fee = $fee;
	}

	public function getFoundAt()
	{
		return Carbon::parse($this->found_at . '.' . sprintf('%06d', $this->found_at_milliseconds * 1000));
	}

	public function getTag()
	{
		return $this->tag;
	}

	public function getHash()
	{
		return $this->hash;
	}

	public function getT()
	{
		return $this->t;
	}

	public function getPos()
	{
		return $this->pos;
	}

	public function getPayout()
	{
		return $this->payout;
	}

	public function getFee()
	{
		return $this->fee;
	}
}
