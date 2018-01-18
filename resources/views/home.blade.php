@extends('layouts.app')

@section('title')
	Home
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					XDAG - Dagger pool
				</h1>
				<h2 class="subtitle">
					<span class="tooltip" data-tooltip="Location: Slovakia, Europe">
						High availability mining pool
					</span>
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('content')
	<div class="home-view">
		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<div class="tabs stat-tabs">
							<ul>
								<li class="is-active" data-target=".pool-stats"><a>Pool statistics</a></li>
								<li data-target=".network-stats"><a>Network statistics</a></li>
							</ul>
						</div>
					</header>

					<div class="card-content stats">
						<nav class="level is-mobile pool-stats">
							<div class="level-item has-text-centered tooltip" data-tooltip="Past hour hashrate. Click for details.">
								<div>
									<p class="heading">Hashrate</p>
									<p class="title">
										<a href="{{ route('stats') }}" class="stat api is-loading" data-stat="pool_hashrate"></a>
									</p>
								</div>
							</div>
							<div class="level-item has-text-centered tooltip" data-tooltip="Active miners">
								<div>
									<p class="heading">Miners</p>
									<p class="title">
										<a href="{{ route('stats') }}" class="stat api is-loading" data-stat="miners"></a>
									</p>
								</div>
							</div>
							<div class="level-item has-text-centered stat-tooltip is-tooltip-multiline" data-stat="config">
								<div>
									<p class="heading">Fees</p>
									<p class="title stat api is-loading" data-stat="fees"></p>
								</div>
							</div>
							<div class="level-item has-text-centered stat-tooltip is-tooltip-multiline" data-stat="uptime_exact">
								<div>
									<p class="heading">Uptime</p>
									<p class="title stat api is-loading" data-stat="uptime"></p>
								</div>
							</div>
						</nav>
						<nav class="level is-mobile network-stats">
							<div class="level-item has-text-centered tooltip" data-tooltip="Past hour hashrate">
								<div>
									<p class="heading">Hashrate</p>
									<p class="title">
										<a href="{{ route('stats') }}" class="stat api is-loading" data-stat="network_hashrate"></a>
									</p>
								</div>
							</div>
							<div class="level-item has-text-centered tooltip" data-tooltip="Number of known blocks">
								<div>
									<p class="heading">Blocks</p>
									<p class="title stat api is-loading" data-stat="blocks"></p>
								</div>
							</div>
							<div class="level-item has-text-centered stat-tooltip" data-stat="supply" data-stat-prefix="Coin supply: ">
								<div>
									<p class="heading">Main blocks</p>
									<p class="title stat api is-loading" data-stat="main_blocks"></p>
								</div>
							</div>
							<div class="level-item has-text-centered stat-tooltip" data-stat="difficulty_exact">
								<div>
									<p class="heading">Difficulty</p>
									<p class="title stat api is-loading" data-stat="difficulty"></p>
								</div>
							</div>
						</nav>
					</div>
				</nav>
			</div>
		</div>

		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<p class="card-header-title">
							Mining information
						</p>
					</header>

					<div class="card-content">
						<p class="offset">Windows (<a href="{{ route('pages', 'setup/windows') }}">detailed instructions</a>):</p>
						<pre class="oneline">
							<span class="parameter">C:\xdag</span>\xdag.exe -d -m <span class="parameter">4</span> pool.xdagpool.com:13654
						</pre>
						<p>Replace <span class="parameter">C:\xdag</span> with full path to your xdag installation folder.</p>
						<p>Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads.</p>
						<p><span class="important">WARNING:</span> if your machine's clock is not set to GMT timezone, follow detailed windows instructions.</p>

						<hr>

						<p>Unix (<a href="{{ route('pages', 'setup/unix') }}">detailed instructions</a>):</p>
						<pre class="oneline">
							TZ=GMT ./xdag -d -m <span class="parameter">4</span> pool.xdagpool.com:13654
						</pre>
						<p>Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads.</p>
					</div>
				</nav>
			</div>
		</div>

		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<p class="card-header-title">
							Optional registration
						</p>
					</header>

					<div class="card-content">
						<div class="content">
							Register if you want to easily keep track of your miners, their hashrates, balances, and receive email notifications should your miner go offline (<span class="important">Coming soon!</span>).
						</div>
					</div>
				</nav>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		var homeView = new homeView();
	</script>
@endsection
