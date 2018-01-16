<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
	public function index($page)
	{
		$pages = ['setup/unix', 'setup/windows'];

		if (in_array($page, $pages))
			return view('pages.' . str_replace('/', '.', $page));

		return redirect()->route('home');
	}
}
