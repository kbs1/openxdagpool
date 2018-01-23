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
				<div class="notification" id="balanceResult">
					<button class="delete"></button>
					<span></span>
				</div>

				<nav class="card">
					<header class="card-header">
						<div class="tabs stat-tabs">
							<ul>
								<li class="is-active" data-target=".pool-stats"><a>Pool statistics</a></li>
								<li data-target=".network-stats"><a>Network statistics</a></li>
								@if (!Auth::guest())
									<li data-target=".user-stats"><a>{{ Auth::user()->display_nick }}'s statistics</a></li>
								@endif
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
							<div class="level-item has-text-centered tooltip" data-tooltip="Currently active miners. Click for details.">
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
						<nav class="level is-mobile network-stats inactive-tab-stats">
							<div class="level-item has-text-centered tooltip" data-tooltip="Past hour hashrate. Click for details.">
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
						@if (!Auth::guest())
							<nav class="level is-mobile user-stats inactive-tab-stats">
								<div class="level-item has-text-centered tooltip" data-tooltip="Your estimated hashrate. Click for details.">
									<div>
										<p class="heading">Hashrate</p>
										<p class="title">
											<a href="{{ route('miners') }}" class="stat api is-loading" data-stat="user_hashrate"></a>
										</p>
									</div>
								</div>
								<div class="level-item has-text-centered tooltip" data-tooltip="Your active miners (machines). Click for details.">
									<div>
										<p class="heading">Miners</p>
										<p class="title">
											<a href="{{ route('miners') }}" class="stat api is-loading" data-stat="user_miners"></a>
										</p>
									</div>
								</div>
								<div class="level-item has-text-centered stat-tooltip" data-stat="user_earnings" data-stat-prefix="Earnings: ">
									<div>
										<p class="heading">Coins</p>
										<p class="title">
											<a href="{{ route('miners') }}" class="stat api is-loading" data-stat="user_balance"></a>
										</p>
									</div>
								</div>
								<div class="level-item has-text-centered tooltip is-tooltip-multiline" data-tooltip="Out of all pool users with registered miners, this is how your hashrate compares to them.">
									<div>
										<p class="heading">Rank</p>
										<p class="title stat api is-loading" data-stat="user_rank"></p>
									</div>
								</div>
							</nav>
						@endif
					</div>
				</nav>
			</div>
		</div>

		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<p class="card-header-title">
							Wallet balance
						</p>
					</header>

					<div class="card-content">
						<div class="content">
							<form action="#" method="post" id="balanceCheckForm">
								<div class="field has-addons is-horizontal">
									<div class="control is-expanded">
										<input class="input is-fullwidth" type="text" name="address" placeholder="Wallet address" maxlength="32" required>
									</div>
									<div class="control">
										<button class="button tooltip" data-tooltip="Balance updates every 4 hours." type="submit">
											Show
										</button>
									</div>
								</div>
							</form>
						</div>
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
						<p>Windows (<a href="{{ route('pages', 'setup/windows') }}">detailed instructions</a>):</p>
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
							Register if you want to easily keep track of your miners, their hashrates, balances, payouts and receive email notifications should your miner go offline.
						</div>
					</div>
				</nav>
			</div>
		</div>

		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<p class="card-header-title">
							Pool news
						</p>
					</header>

					<div class="card-content">
						<div class="content">
							<ul>
								<li><span class="important">2018-01-23</span> Added detailed payment statistics</li>
								<li><span class="important">2018-01-22</span> Updated pool software</li>
								<li><span class="important">2018-01-21</span> Support for "went offline" and "back online" miner alerts</li>
								<li><span class="important">2018-01-20</span> Updated pool software, added wallet balance checker</li>
								<li><span class="important">2018-01-19</span> Added user's stats on the homepage</li>
								<li><span class="important">2018-01-17</span> Added detailed network graphs</li>
								<li><span class="important">2018-01-16</span> Added option to register your miners, updated pool software</li>
								<li><span class="important">2018-01-15</span> Pool launched</li>
							</ul>
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
