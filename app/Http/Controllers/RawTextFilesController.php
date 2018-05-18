<?php

namespace App\Http\Controllers;

use App\Pool\DataReader;

class RawTextFilesController extends Controller
{
	protected $reader;

	public function __construct(DataReader $reader)
	{
		$this->reader = $reader;
	}

	public function index($file)
	{
		$data = null;

		if ($file == 'stats')
			$data = stream_get_contents($this->reader->getStatistics());
		else if ($file == 'state')
			$data = stream_get_contents($this->reader->getState());

		if (!$data)
			return redirect()->route('home');

		return response($data, 200)->header('Content-Type', 'text/plain');
	}
}
