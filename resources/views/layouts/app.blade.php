<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@yield('title', 'Pool') | {{ config('app.name', 'Laravel') }}</title>
		<link href="{{ mix('css/app.css') }}" rel="stylesheet">
	</head>
	<body>
		<div id="app">
			<nav class="navbar has-shadow">
				<div class="container">
					<div class="navbar-brand">
						<a href="{{ url('/') }}" class="navbar-item">Home</a>

						<div class="navbar-burger burger">
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>

					<div class="navbar-menu" id="navMenu">
						<div class="navbar-start">
							<div class="navbar-item has-dropdown is-hoverable">
								<a class="navbar-link" href="#">Resources</a>

								<div class="navbar-dropdown">
									<a class="navbar-item" href="http://cheatcoin.atwebpages.com" target="_blank">XDAG website</a>
									<a class="navbar-item" href="https://bitcointalk.org/index.php?topic=2552368" target="_blank">Bitcointalk thread</a>

									<hr class="navbar-divider">

									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'setup.windows' ? ' is-active' : '' !!}" href="{{ route('pages', 'setup/windows') }}">Windows miner setup</a>
									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'setup.unix' ? ' is-active' : '' !!}" href="{{ route('pages', 'setup/unix') }}">Unix miner setup</a>
								</div>
							</div>
						</div>

						<div class="navbar-end">
							@if (Auth::guest())
								<a class="navbar-item{!! isset($activeTab) && $activeTab == 'login' ? ' is-active' : '' !!}" href="{{ route('login') }}">Login</a>
								<a class="navbar-item{!! isset($activeTab) && $activeTab == 'register' ? ' is-active' : '' !!}" href="{{ route('register') }}">Register</a>
							@else
								<div class="navbar-item has-dropdown is-hoverable">
									<a class="navbar-link" href="{{ route('profile') }}">{{ Auth::user()->nick }}</a>

									<div class="navbar-dropdown">
										<a class="navbar-item{!! isset($activeTab) && $activeTab == 'profile' ? ' is-active' : '' !!}" href="{{ route('profile') }}">Profile</a>
										<a class="navbar-item{!! isset($activeTab) && $activeTab == 'miners' ? ' is-active' : '' !!}" href="{{ route('miners') }}">Miners</a>

										<hr class="navbar-divider">

										<a class="navbar-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>

										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											{{ csrf_field() }}
										</form>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			</nav>

			@yield('hero')

			<div class="columns is-marginless is-centered">
				<div class="column is-7">
					@if (count($errors) > 0)
						<div class="notification is-warning">
							<button class="delete"></button>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					@if (Session::has('success'))
						<div class="notification is-success">
							<button class="delete"></button>
							{{ Session::get('success') }}
						</div>
					@endif

					@if (Session::has('warning'))
						<div class="notification is-warning">
							<button class="delete"></button>
							{{ Session::get('warning') }}
						</div>
					@endif

					@if (Session::has('error'))
						<div class="notification is-danger">
							<button class="delete"></button>
							{{ Session::get('error') }}
						</div>
					@endif
				</div>
			</div>

			@yield('content')

			<div class="container">
				<div class="content">
					<hr>
					<p id="footer" class="is-pulled-right">
						XDAGpool.com,
						@php
							echo date('Y');
						@endphp
					</p>
				</div>
			</div>
		</div>

		<script src="{{ mix('js/app.js') }}"></script>
		<script>
			var appView = new appView();
		</script>
		@yield('scripts')
	</body>
</html>
