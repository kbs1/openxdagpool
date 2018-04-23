<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
	public function index($page)
	{
		$pages = ['setup/unix-cpu', 'setup/unix-gpu', 'setup/windows-cpu', 'setup/windows-gpu'];

		if (in_array($page, $pages))
			return view('pages.' . str_replace('/', '.', $page), [
				'activeTab' => str_replace('/', '.', $page),
			]);

		return redirect()->route('home');
	}
}
