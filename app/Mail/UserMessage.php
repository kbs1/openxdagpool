<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Users\User;
use Setting;

class UserMessage extends Mailable
{
	use Queueable, SerializesModels;

	protected $user, $mail_subject, $content;

	public function __construct(User $user, $mail_subject, $content)
	{
		$this->user = $user;
		$this->mail_subject = $mail_subject;
		$this->content = $content;
	}

	public function build()
	{
		return $this->subject(Setting::get('pool_name') . ': ' . $this->mail_subject)->markdown('emails.user-message')->with([
			'user' => $this->user,
			'subject' => $this->mail_subject,
			'message' => $this->content,
			'pool_name' => Setting::get('pool_name'),
			'website_domain' => Setting::get('website_domain'),
		]);
	}
}
