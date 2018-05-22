@extends('layouts.app')

@section('title')
	Statistics
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Statistics
				</h1>
				<h2 class="subtitle">
					Hash rates and active miners count
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('content')
	<div class="stats-view">
		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<nav class="card">
					<header class="card-header">
						<div class="tabs stat-tabs">
							<ul>
								<li class="is-active" data-target="all"><a>All</a></li>
								<li data-target=".pool-hashrate"><a>Pool hashrate</a></li>
								<li data-target=".active-miners"><a>Active miners</a></li>
								<li data-target=".found-blocks"><a>Found blocks</a></li>
								<li data-target=".network-hashrate"><a>Network hashrate</a></li>
							</ul>
						</div>
					</header>

					<div class="card-content stats">
						<div class="content chart-container pool-hashrate">
							<div class="chart api is-loading"></div>
						</div>
						<div class="content chart-container not-first active-miners">
							<div class="chart api is-loading"></div>
						</div>
						<div class="content chart-container not-first found-blocks">
							<div class="chart api is-loading"></div>
						</div>
						<div class="content chart-container not-first network-hashrate">
							<div class="chart api is-loading"></div>
						</div>
					</div>
				</nav>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		var statsView = new statsView();
	</script>
@endsection
