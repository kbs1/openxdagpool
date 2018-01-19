@extends('layouts.app')

@section('title')
	Windows miner setup (Windows 10 64-bit)
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Windows miner setup
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
							<li>Download <code>win64exe.zip</code> from the <a href="http://xdag.me/downloads.html" target="_blank">official website</a>.</li>
							<li>Extract the archive into <code>C:\xdag</code></code></li>
							<li>Click on start, type <code>run</code>, press enter, type <code>cmd</code>, press enter.</li>
							<li>Type <code>cd C:\xdag</code> and press enter.</li>
							<li>Type <code>xdag -d -m 1 pool.xdagpool.com:13654</code> and press enter.</li>
							<li>Set your wallet password, type random keys. Once the host keys are generated (might take a while), type <code>terminate</code> and press enter.</li>
							<li>Create a new bat file <code>C:\xdag\RUNMINER.bat</code></li>
							<li>Edit with notepad. If your system clock is already set to GMT, insert one line into the file: <pre class="oneline">C:\xdag\xdag.exe -d -m <span class="parameter">4</span> pool.xdagpool.com:13654</pre> Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads. If your system clock is not set to GMT, see the next section.</li>
							<li>Double click the <code>RUNMINER.bat</code> file, type your wallet password, your miner will now start. Do not close the console window at any time.</li>
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
						For machines outside the GMT timezone
					</p>
				</header>

				<div class="card-content">
					<div class="content">
						<ol>
							<li>Download <a href="https://www.nirsoft.net/utils/runasdate-x64.zip" target="_blank">RunAsDate.exe</a>.</li>
							<li>Extract just the executable into the <code>C:\xdag</code> directory.</li>
							<li>In step 8 above, paste the following line into the <code>RUNMINER.bat</code> file:
								<pre class="oneline">
									runasdate /movetime /startin "C:\xdag" Hours:<span class="parameter">-1</span> "C:\xdag\xdag.exe" -d -m <span class="parameter">4</span> pool.xdagpool.com:13654
								</pre>
								<p>Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads.</p>
								<p>Replace <span class="parameter">-1</span> with your current offset to GMT timezone if your machine's clock is not set to GMT.</p>
							</li>
							<li>Continue with step 9.</li>
						</ol>
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
							<li>To update your installation to the latest version, stop the miner by typing <code>terminate</code> in the miner console, press enter. Download new <code>win64exe.zip</code> from the <a href="http://xdag.me/downloads.html" target="_blank">official website</a>. Extract the archive into <code>C:\xdag2</code>. Copy over files and folders <code>storage</code>, <code>dnet_key.dat</code>, <code>wallet.dat</code>, <code>RUNMINER.bat</code> and <code>RunAsDate.exe</code> (if applicable) from the old <code>C:\xdag</code> installation. Move <code>C:\xdag</code> into the trash. Rename <code>C:\xdag2</code> to <code>C:\xdag</code>. Run <code>RUNMINER.bat</code> as usual.</li>
						</ol>
						<p>To view your current balance at any time, type <code>balance</code> in the miner console.</p>
					</div>
				</div>
			</nav>
		</div>
	</div>
@endsection
