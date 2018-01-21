<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Miners\Miner;

class MinerWentOffline extends Mailable
{
	use Queueable, SerializesModels;

	protected $miner;

	public function __construct(Miner $miner)
	{
		$this->miner = $miner;
	}

	public function build()
	{
		$miner = $this->miner;
		$subject = config('app.name') . ': miner ' . $miner->short_address . ($miner->note ? ' (' . $miner->short_note . ')' : '') . ' went offline';
		return $this->subject($subject)->markdown('emails.miner-went-offline')->with([
			'miner' => $miner,
		]);
	}
}
