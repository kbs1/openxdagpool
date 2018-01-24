<?php

namespace App\Pool;

class BaseParser
{
	protected $handle;

	public function __construct($handle)
	{
		$this->handle = $handle;
	}

	public function forEachLine(callable $callback, $skip = 0)
	{
		if (!$this->handle)
			return;

		$line_number = 0;

		while (($line = fgets($this->handle)) !== false) {
			if (substr($line, 0, 6) === 'xdag> ')
				$line = substr($line, 6);

			$line = trim($line);

			if ($line !== '') {
				if ($skip <= $line_number)
					$callback($line);

				$line_number++;
			}
		}

		fseek($this->handle, 0);
	}
}
