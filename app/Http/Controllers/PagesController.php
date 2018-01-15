<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
	public function index($page)
	{
		$pages = ['unix', 'windows'];

		if (in_array($page, $pages))
			return view("pages.$page");

		return redirect()->route('home');
	}
}
