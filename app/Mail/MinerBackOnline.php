<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Miners\Miner;
use Setting;

class MinerBackOnline extends Mailable
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
		$subject = Setting::get('pool_name') . ': miner ' . $miner->short_address . ($miner->note ? ' (' . $miner->short_note . ')' : '') . ' back online';
		return $this->subject($subject)->markdown('emails.miner-back-online')->with([
			'miner' => $miner,
			'pool_name' => Setting::get('pool_name'),
			'website_domain' => Setting::get('website_domain'),
		]);
	}
}
