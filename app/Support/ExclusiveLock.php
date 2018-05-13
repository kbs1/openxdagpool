<?php

namespace App\Support;

class ExclusiveLock
{
	protected $name, $timeout, $file_path, $file_handle = null, $locked = false;

	public function __construct($name, $timeout = 100)
	{
		$this->name = $name;
		$this->timeout = max(0, intval($timeout));

		if (!$this->isNameValid())
			throw new InvalidLockName("Lock name '$name' is not valid.");

		$this->file_path = storage_path('lock_' . md5($name));
	}

	public function __destruct()
	{
		$this->release();
	}

	public function obtain()
	{
		if ($this->locked)
			return;

		$spent = 0;
		do {
			$this->file_handle = @fopen($this->file_path, 'x');

			if ($this->file_handle)
				break;

			sleep(1);
			$spent++;
		} while ($spent < $this->timeout);

		if ($this->file_handle === false)
			throw new UnableToObtainLockException("Could not obtain exclusive '" . $this->name . "' lock, please try again later.");

		$this->locked = true;
	}

	public function release()
	{
		if (!$this->locked)
			return;

		fclose($this->file_handle);
		@unlink($this->file_path);
		$this->locked = false;
	}

	protected function isNameValid()
	{
		return $this->name != '' && strlen($this->name) <= 50 && preg_match('/^[0-9a-z_-]+$/ui', $this->name);
	}
}

class InvalidLockName extends \InvalidArgumentException {};

class ExclusiveLockException extends \Exception {};
class UnableToObtainLockException extends ExclusiveLockException {};
