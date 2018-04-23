@extends('layouts.app')

@section('title')
	Unix CPU miner setup (Ubuntu 16.04)
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Unix CPU miner setup
				</h1>
				<h2 class="subtitle">
					For Ubuntu 16.04
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
							<li><code>cd</code> to your home directory. Do not run the miner as <code>root</code>!</li>
							<li>Execute: <code>sudo apt-get install gcc libssl-dev build-essential git</code></li>
							<li>Execute: <code>git clone https://github.com/XDagger/xdag.git</code></li>
							<li>Change directory: <code> cd ./xdag/client</code></li>
							<li>Run <code>make</code></li>
							<li>Run the program with <code>./xdag -d -m 1 {{ Setting::get('pool_domain') }}:{{ Setting::get('pool_port') }}</code>. Set up your wallet password, type random keys (at least 3 lines of random keys). Wait until host keys are generated.</li>
							<li>Execute <code>./xdag -i</code>. Type <code>terminate</code> and press enter.</li>
							<li><code>cd</code> to your home directory</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_console.sh
#!/bin/bash

pidof xdag > /dev/null

if [ "$?" -ne 0 ]; then
	echo "Daemon not running! Start it with ./xdag_run.sh"
	exit 1
fi

echo Starting console...
(cd ./xdag/client &amp;&amp; ./xdag -i)
echo -n "Daemon PIDs: "
pidof xdag
EOD</pre>
							</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_run.sh
#!/bin/bash

PIDS="`pidof xdag`"

if [ "$?" -eq 0 ]; then
	echo "Daemon already running? PIDs: ${PIDS}"
	echo "run ./xdag_console.sh and type 'terminate' to terminate the daemon."
	exit 1
fi

echo Starting daemon...
(cd ./xdag/client &amp;&amp; ./xdag -d -m <span class="parameter">4</span> {{ Setting::get('pool_domain') }}:{{ Setting::get('pool_port') }})
echo -n "Daemon PIDs: "
pidof xdag
EOD</pre>Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads. You can control this later by typing <code>mining N</code> in the XDAG console, where <span class="parameter">N</span> is the number of mining threads you want to run.
							</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_update.sh
#!/bin/bash

PIDS="`pidof xdag`"

if [ "$?" -eq 0 ]; then
	echo "Daemon is running! Stop it before updating. PIDs: ${PIDS}"
	echo "run ./xdag_console.sh and type 'terminate' to terminate the daemon."
exit 1
fi

echo Updating git repository...
(cd ./xdag &amp;&amp; git pull &amp;&amp; cd ./client &amp;&amp; make)

echo "Done! Start the daemon with ./xdag_run.sh"
EOD</pre>
							</li>
							<li>Execute <code>chmod +x xdag_*</code></li>
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
							<li>To start the miner, simply execute <code>./xdag_run.sh</code> in your home folder, type in your wallet password.</li>
							<li>To connect to the miner console at any time, execute <code>./xdag_console.sh</code>.</li>
							<li>To conveniently update your installation to the latest version, stop the deamon by executing <code>./xdag_console.sh</code>, type <code>terminate</code>, press enter, run <code>./xdag_update.sh</code>, then <code>./xdag_run.sh</code> and type your wallet password again.</li>
							<li>To view your current balance at any time, type <code>balance</code> in the miner console. To show your wallet address, type <code>account</code> in the miner console. You can also use our website to check your balance at any time on the home page, or <a href="{{ route('register') }}">register</a> your miner to automatically show it's balance, payouts, unpaid shares and more.</li>
						</ol>
					</div>
				</div>
			</nav>
		</div>
	</div>
@endsection
