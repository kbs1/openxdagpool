<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@yield('title', 'Pool') | {{ Setting::get('pool_name', 'OpenXDAGPool') }}</title>
		<link href="{{ mix('css/app.css') }}" rel="stylesheet">
		<style type="text/css">
			.hero {
				background-color: {{ $headerBackgroundColor }} !important;
			}
		</style>
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
								<a class="navbar-link" href="{{ route('stats') }}">Resources</a>

								<div class="navbar-dropdown">
									<a class="navbar-item" href="http://xdag.io" target="_blank">XDAG website</a>
									<a class="navbar-item" href="https://explorer.xdag.io" target="_blank">XDAG explorer</a>
									<a class="navbar-item" href="https://bitcointalk.org/index.php?topic=2552368" target="_blank">Bitcointalk thread</a>

									<hr class="navbar-divider">

									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'setup.windows-gpu' ? ' is-active' : '' !!}" href="{{ route('pages', 'setup/windows-gpu') }}">Windows GPU miner setup</a>
									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'setup.windows-cpu' ? ' is-active' : '' !!}" href="{{ route('pages', 'setup/windows-cpu') }}">Windows CPU miner setup</a>
									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'setup.unix-gpu' ? ' is-active' : '' !!}" href="{{ route('pages', 'setup/unix-gpu') }}">Unix GPU miner setup</a>
									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'setup.unix-cpu' ? ' is-active' : '' !!}" href="{{ route('pages', 'setup/unix-cpu') }}">Unix CPU miner setup</a>

									<hr class="navbar-divider">

									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'stats' ? ' is-active' : '' !!}" href="{{ route('stats') }}">Statistics</a>
									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'found-blocks' ? ' is-active' : '' !!}" href="{{ route('found-blocks') }}">Found blocks</a>
									<a class="navbar-item{!! isset($activeTab) && $activeTab == 'leaderboard' ? ' is-active' : '' !!}" href="{{ route('leaderboard') }}">Leaderboard</a>
								</div>
							</div>
						</div>

						<div class="navbar-end">
							@if (Auth::guest())
								<a class="navbar-item{!! isset($activeTab) && $activeTab == 'login' ? ' is-active' : '' !!}" href="{{ route('login') }}">Login</a>
								<a class="navbar-item{!! isset($activeTab) && $activeTab == 'register' ? ' is-active' : '' !!}" href="{{ route('register') }}">Register</a>
							@else
								<div class="navbar-item has-dropdown is-hoverable">
									<a class="navbar-link" href="{{ route('miners') }}">{{ Auth::user()->display_nick }}</a>

									<div class="navbar-dropdown">
										<a class="navbar-item{!! isset($activeTab) && $activeTab == 'profile' ? ' is-active' : '' !!}" href="{{ route('profile') }}">Profile</a>
										<a class="navbar-item{!! isset($activeTab) && $activeTab == 'miners' ? ' is-active' : '' !!}" href="{{ route('miners') }}">Miners</a>
										<a class="navbar-item{!! isset($activeTab) && $activeTab == 'payouts' ? ' is-active' : '' !!}" href="{{ route('user.payouts.graph') }}">Payouts</a>
										<a class="navbar-item{!! isset($activeTab) && $activeTab == 'hashrate' ? ' is-active' : '' !!}" href="{{ route('user.hashrate.graph', 'latest') }}">Hashrate</a>

										@if ($authUser->isAdministrator())
											<hr class="navbar-divider">
											<a class="navbar-item{!! isset($activeTab) && $activeTab == 'admin' ? ' is-active' : '' !!}" href="{{ route('user.admin.users') }}">Admin</a>
										@endif

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

			@if (count($errors) > 0 || Session::has('success') || Session::has('warning') || Session::has('error'))
				<div class="columns is-marginless is-centered">
					<div class="column is-@yield('flashMessagesWidth', '7')">
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
			@else
				<div class="page-padding"></div>
			@endif

			@yield('content')

			<div class="container">
				<div class="content">
					<hr>
					<a href="#" id="footer" class="is-pulled-right">
						<span class="tooltip" data-tooltip="Powered by OpenXDAGPool">{{ Setting::get('website_domain') }}</span>,
						@php
							echo date('Y');
						@endphp
					</a>
				</div>
			</div>

			<div class="modal" id="contactUsModal">
				<div class="modal-background"></div>
				<div class="modal-card">
					<header class="modal-card-head">
						<p class="modal-card-title">Contact us</p>
						<a class="delete close-modal" aria-label="close" href="#"></a>
					</header>
					<section class="modal-card-body">
						<p>Questions, comments, or suggestions? Contact us at <span id="contactEmail" data-transform-applied="false">{{ base64_encode(str_replace(['@', '.'], ['&', '*'], str_rot13($contactEmail))) }}</span>.</p>
						<p>View the OpenXDAGPool project at <a href="https://github.com/XDagger/openxdagpool" target="_blank">GitHub</a>.</p>
					</section>
					<footer class="modal-card-foot">
						<button type="button" class="button close-modal">Close</button>
					</footer>
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
