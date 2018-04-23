@extends('layouts.app')

@section('title')
	Windows CPU miner setup (Windows 10 64-bit)
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Windows CPU miner setup
				</h1>
				<h2 class="subtitle">
					For Windows 10 64-bit
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('content')
	<div class="columns is-marginless is-centered">
		<div class="column is-7">
			<nav class="card">
				<header class="card-header">
					<p class="card-header-title">
						Initial setup
					</p>
				</header>

				<div class="card-content">
					<div class="content">
						<ol>
							<li>Download <code>XDag.x64.zip</code> from the <a href="https://github.com/XDagger/xdag/releases" target="_blank">official repository</a>.</li>
							<li>Extract the archive into <code>C:\xdag</code></li>
							<li>Create a new bat file <code>C:\xdag\RUNMINER.bat</code></li>
							<li>Insert one line into the file: <pre class="oneline">C:\xdag\xdag.exe -d -m <span class="parameter">4</span> {{ Setting::get('pool_domain') }}:{{ Setting::get('pool_port') }}</pre> Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads.</li>
							<li>Double click the <code>RUNMINER.bat</code> file, set your wallet password, type random keys (at least 3 lines of random keys). Once the host keys are generated (might take a while), miner will start. Do not close the console window at any time.</li>
						</ol>
						<p>Done! For usage, see the next usage section.</p>
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
						Usage
					</p>
				</header>

				<div class="card-content">
					<div class="content">
						<ol>
							<li>To start the miner, simply run the <code>RUNMINER.bat</code> file.</li>
							<li>To update your installation to the latest version, stop the miner by typing <code>terminate</code> in the miner console, press enter. Download new <code>XDag.x64.zip</code> from the <a href="https://github.com/XDagger/xdag/releases" target="_blank">official repository</a>. Extract the archive into <code>C:\xdag</code>, overwriting the files. Run <code>RUNMINER.bat</code> as usual.</li>
							<li>To view your current balance at any time, type <code>balance</code> in the miner console. If you want to see your wallet address, type <code>account</code> into the miner console. To copy your wallet address, select it with your mouse and press enter. You can also use our website to check your balance at any time on the home page, or <a href="{{ route('register') }}">register</a> your miner to automatically show it's balance, payouts, unpaid shares and more.</li>
						</ol>
					</div>
				</div>
			</nav>
		</div>
	</div>
@endsection
