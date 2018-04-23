@extends('layouts.app')

@section('title')
	Unix GPU miner setup (Ubuntu 16.04)
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Unix GPU miner setup
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
						First set up your XDAG wallet.
						<ol>
							<li><code>cd</code> to your home directory. Do not run the wallet as <code>root</code>!</li>
							<li>Execute: <code>sudo apt-get install gcc libssl-dev build-essential git</code></li>
							<li>Execute: <code>git clone https://github.com/XDagger/xdag.git</code></li>
							<li>Change directory: <code> cd ./xdag/client</code></li>
							<li>Run <code>make</code></li>
							<li>Run the wallet with <code>./xdag -d -m 1 {{ Setting::get('pool_domain') }}:{{ Setting::get('pool_port') }}</code>. Set up your wallet password, type random keys (at least 3 lines of random keys). Wait until host keys are generated.</li>
							<li>Execute <code>./xdag -i</code>. Type <code>terminate</code> and press enter to close your wallet.</li>
							<li><code>cd</code> to your home directory</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_wallet_console.sh
#!/bin/bash

pidof xdag > /dev/null

if [ "$?" -ne 0 ]; then
	echo "Wallet not running! Start it with ./xdag_wallet_run.sh"
	exit 1
fi

echo Starting wallet console...
(cd ./xdag/client &amp;&amp; ./xdag -i)
echo -n "Wallet PIDs: "
pidof xdag
EOD</pre>
							</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_wallet_run.sh
#!/bin/bash

PIDS="`pidof xdag`"

if [ "$?" -eq 0 ]; then
	echo "Wallet already running? PIDs: ${PIDS}"
	echo "run ./xdag_wallet_console.sh and type 'terminate' to terminate the wallet."
	exit 1
fi

echo Starting wallet...
(cd ./xdag/client &amp;&amp; ./xdag -d -m 1 {{ Setting::get('pool_domain') }}:{{ Setting::get('pool_port') }})
echo -n "Wallet PIDs: "
pidof xdag
EOD</pre>
							</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_wallet_update.sh
#!/bin/bash

PIDS="`pidof xdag`"

if [ "$?" -eq 0 ]; then
	echo "Wallet is running! Stop it before updating. PIDs: ${PIDS}"
	echo "run ./xdag_wallet_console.sh and type 'terminate' to terminate the wallet."
exit 1
fi

echo Updating git repository...
(cd ./xdag &amp;&amp; git pull &amp;&amp; cd ./client &amp;&amp; make)

echo "Done! Start the wallet with ./xdag_wallet_run.sh"
EOD</pre>
							</li>
							<li>Execute <code>chmod +x xdag_*</code></li>
						</ol>
						<p>Your wallet is now ready. Next the GPU miner will be set up.</p>

						<ol>
							<li>As <code>root</code>, install <a href="https://developer.amd.com/amd-accelerated-parallel-processing-app-sdk/" target="_blank">AMD APP SDK</a> that matches your distribution. This is necessary for both AMD and NVIDIA cards.</li>
							<li>Install graphics card drivers for <a href="https://support.amd.com/en-us/download/linux" target="_blank">AMD</a> or <a href="http://www.nvidia.com/object/unix.html" target="_blank">NVIDIA</a> depending on which cards you have.</li>
							<li><code>cd</code> to your home directory. Do not run the GPU miner as <code>root</code>!</li>
							<li>Execute: <code>sudo apt-get install git gcc libssl-dev make ocl-icd-opencl-dev libboost-all-dev screen</code></li>
							<li>Execute: <code>git clone https://github.com/jonano614/DaggerGpuMiner.git</code></li>
							<li>Execute: <code>cd DaggerGpuMiner/GpuMiner</code></li>
							<li>Execute: <code>make all</code></li>
							<li><code>cd</code> to your home directory.</li>
							<li>Execute: <code>./xdag_wallet_run.sh</code>. Type your wallet password, then execute <code>./xdag_wallet_console.sh</code>. Type <code>account</code>. Copy your wallet address. Type <code>terminate</code> to close your wallet.</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_miner_run.sh
#!/bin/bash

PIDS="`pidof xdag-gpu`"

if [ "$?" -eq 0 ]; then
	echo "Miner already running? PIDs: ${PIDS}"
	echo "run 'screen -x' and press CTRL+C to terminate the miner."
	exit 1
fi

if [ "$STY" == "" ]; then
	echo "Please execute 'screen' first before executing this script."
	exit 1
fi

echo Starting miner...
(cd ./DaggerGpuMiner/GpuMiner &amp;&amp; ./xdag-gpu -G -a <span class="parameter">wallet_address</span> -p {{ Setting::get('pool_domain') }}:{{ Setting::get('pool_port') }} -t 0 -v 2 -opencl-platform <span class="parameter">platform_id</span> -opencl-devices <span class="parameter">device_nums</span>)
echo -n "Miner PIDs: "
pidof xdag-gpu
EOD</pre>
								Replace <span class="parameter">wallet_address</span> with the address you copied. Replace <span class="parameter">platform_id</span> with your OpenCL platform ID, this is most usually <code>0</code> (also try <code>1</code> or <code>2</code> if necessary). If you have more than one GPU in the system, count up from zero to (number of devices - 1), so for example if you have 4 GPUs in your system, replace <span class="parameter">device_nums</span> with <code>0 1 2 3</code>. If you have only one GPU, replace <span class="parameter">device_nums</span> with <code>0</code>. To see advanced GPU miner parameters, execute <code>./xdag-gpu -h</code>.
							</li>
							<li>Execute:
<pre>cat << 'EOD' > ./xdag_miner_update.sh
#!/bin/bash

PIDS="`pidof xdag-gpu`"

if [ "$?" -eq 0 ]; then
	echo "Miner is running! Stop it before updating. PIDs: ${PIDS}"
	echo "run 'screen -x' and press CTRL+C to terminate the miner."
	exit 1
fi

echo Updating git repository...
(cd ./DaggerGpuMiner &amp;&amp; git pull &amp;&amp; make all)

echo "Done! Start the miner with 'screen ./xdag_miner_run.sh'."
EOD</pre>
							</li>
							<li>Execute <code>chmod +x xdag_*</code></li>
							<li>Execute: <code>screen ./xdag_miner_run.sh</code>. Once the miner starts running, hold down CTRL and press keys a, then d. This will detach the screen program and return to your shell, with the miner stll running. You can now disconnect from the machine.</li>
						</ol>
						<p>Done! Your GPU miner is now running. For usage, see the next usage section.</p>
						<p><span class="important">Note:</span> if you are using NVIDIA GPUs, make sure you add <code>-nvidia-fix</code> at the end of the GPU miner command line in step 10 to prevent high system CPU usage and increase your hashrate.</p>
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
							<li>To start the miner, execute <code>screen ./xdag_miner_run.sh</code> in your home folder and detach from screen by holding CTRL and pressing letters a, then d.</li>
							<li>To view your miner status at any time execute <code>screen -x</code>.</li>
							<li>To update your installation to the latest version, execute <code>screen -x</code> and stop the miner by pressing <code>CTRL+C</code>. Execute <code>./xdag_miner_update.sh</code>. After this is done, execute <code>screen ./xdag_miner_run.sh</code>.</li>
							<li>To view your current balance at any time, execute <code>./xdag_wallet_run.sh</code> in your home folder, type in your wallet password, press enter, execute <code>./xdag_wallet_console.sh</code>, type <code>balance</code> and press enter. If you see <code>not ready to show balance</code>, wait a bit and type <code>balance</code> again, followed by enter key. When you are done, type <code>terminate</code> followed by enter to close your wallet. You can also use our website to check your balance at any time on the home page, or <a href="{{ route('register') }}">register</a> your miner to automatically show it's balance, payouts, unpaid shares and more.</li>
						</ol>
					</div>
				</div>
			</nav>
		</div>
	</div>
@endsection
