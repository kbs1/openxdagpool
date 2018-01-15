@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<p class="card-header-title">
							XDAG pool
						</p>
					</header>

					<div class="card-content">
						<p>Start mining now:</p>

						<p class="offset">Unix (<a href="{{ route('pages', 'unix') }}">detailed instructions</a>):</p>
						<pre class="oneline">
							TZ=GMT ./xdag -d -m <span class="parameter">4</span> xdagpool.com:13654
						</pre>
						<p>Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads.</p>

						<p class="offset">Windows (<a href="{{ route('pages', 'windows') }}">detailed instructions</a>):</p>
						<pre class="oneline">
							<span class="parameter">C:\xdag</span>\xdag.exe -d -m <span class="parameter">4</span> xdagpool.com:13654
						</pre>
						<p>Replace <span class="parameter">C:\xdag</span> with full path to your xdag installation folder.</p>
						<p>Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads.</p>

						<p><span class="important">WARNING:</span> if your machine's clock is not set to GMT timezone, follow detailed windows instructions.</p>
					</div>
				</nav>
			</div>
		</div>

		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<p class="card-header-title">
							Pool statistics
						</p>
					</header>

					<div class="card-content">
						<div class="content">
							<table class="table">
								<thead>
									<tr>
										<th>Hashrate</th>
										<th>Miners</th>
										<th>Coin</th>
										<th>Total payout</th>
										<th>Uptime</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>204 Gh/s</td>
										<td>534</td>
										<td>XDAG</td>
										<td>331431531.5642524</td>
										<td>1 days, 7 hours, 4 minutes</td>
									</tr>
								</tbody>
							</table>
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
							Optional registration
						</p>
					</header>

					<div class="card-content">
						<div class="content">
							Register today if you want to easily keep track of your miners, their hashrates, payouts, and receive email notifications should your miner go offline.
						</div>
					</div>
				</nav>
			</div>
		</div>
	</div>
@endsection
