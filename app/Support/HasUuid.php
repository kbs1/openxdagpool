<?php

namespace App\Support;

use Uuid;

trait HasUuid
{
	// executed by Eloquent automatically, together with model's normal boot method
	public static function bootHasUuid()
	{
		static::creating(function($model) {
			$model->uuid = (string) Uuid::generate(4);
		});
	}

	public function hasUuid()
	{
		return $this->uuid !== null;
	}
}
