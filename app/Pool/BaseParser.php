<?php

namespace App\Pool;

abstract class BaseParser
{
	protected $data = '';

	public function __construct($data)
	{
		$this->data = $data;
		$this->sanitize();
		$this->parse();
	}

	protected function sanitize()
	{
		$data = explode("\n", $this->data);

		if (count($data) < 8)
			return;

		array_shift($data);

		foreach ($data as &$line) {
			if (substr($line, 0, 6) === 'xdag> ')
				$line = substr($line, 6);

			$line = trim($line);
		}
		unset($line);

		$data = array_values(array_filter($data));

		$this->data = $data;
	}

	abstract protected function parse();
}
