@extends('layouts.app')

@section('content')
	<div class="home-view">
		<section class="hero is-primary">
			<div class="hero-body">
				<div class="container">
					<h1 class="title">
						XDAG - Dagger pool
					</h1>
					<h2 class="subtitle">
						High availability mining pool
					</h2>
				</div>
			</div>
		</section>

		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<p class="card-header-title">
							Pool statistics
						</p>
					</header>

					<div class="card-content stats">
						<nav class="level is-mobile">
							<div class="level-item has-text-centered">
								<div>
									<p class="heading">Hashrate</p>
									<p class="title stat api is-loading" data-stat="hashrate"></p>
								</div>
							</div>
							<div class="level-item has-text-centered">
								<div>
									<p class="heading">Miners</p>
									<p class="title stat api is-loading" data-stat="miners"></p>
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
							Register if you want to easily keep track of your miners, their hashrates, payouts, and receive email notifications should your miner go offline.
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
