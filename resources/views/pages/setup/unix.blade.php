@extends('layouts.app')

@section('title')
	Unix miner setup (Ubuntu 16.04)
@endsection

@section('content')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Unix miner setup
				</h1>
				<h2 class="subtitle">
					For Ubuntu 16.04
				</h2>
			</div>
		</div>
	</section>

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
							<li>Execute: <code>sudo apt-get install git gcc libssl-dev build-essential git</code></li>
							<li>Execute: <code>git clone https://github.com/cheatoshin/cheatcoin.git</code></li>
							<li>Change directory: <code> cd ./cheatcoin/cheatcoin</code></li>
							<li>Run <code>make</code></li>
							<li>Run the program with <code>TZ=GMT ./xdag -d -m 1 pool.xdagpool.com:13654</code>. Set up your wallet password, type random keys. Wait until host keys are generated.</li>
							<li>Execute <code>TZ=GMT ./xdag -i</code>. Type <code>terminate</code> and press enter.</li>
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
(cd ./cheatcoin/cheatcoin &amp;&amp; TZ=GMT ./xdag -i)
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
(cd ./cheatcoin/cheatcoin &amp;&amp; TZ=GMT ./xdag -d -m <span class="parameter">4</span> pool.xdagpool.com:13654)
echo -n "Daemon PIDs: "
pidof xdag
EOD</pre>Replace <span class="parameter">4</span> with number of mining threads, for dedicated mining machines, set this to number of CPU threads. You can control this later by typing <code>mining N</code> in the XDAG console, where <span class="parameter">N</span> is the number of mining threads you want to run.
							</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_update.sh
#!/bin/bash

PIDS="`pidof xdag`"

if [ "$?" -eq 0 ]; then
	echo "Daemon is running! Stop it before recompiling. PIDs: ${PIDS}"
	echo "run ./xdag_console.sh and type 'terminate' to terminate the daemon."
exit 1
fi

echo Updating git repository...
(cd ./cheatcoin &amp;&amp; git pull &amp;&amp; cd ./cheatcoin &amp;&amp; make)

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
						</ol>
						<p>To view your current balance at any time, type <code>balance</code> in the miner console.</p>
					</div>
				</div>
			</nav>
		</div>
	</div>
@endsection
