@extends('layouts.app')

@section('title')
	Windows GPU miner setup (Windows 10 64-bit)
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Windows GPU miner setup
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
							<li>Download latest <code>x64</code> zip package from <a href="https://github.com/jonano614/DaggerGpuMiner/releases" target="_blank">GitHub</a>.</li>
							<li>Extract the archive into <code>C:\DaggerGpuMiner</code></li>
							<li>Download <code>XDag.x64.zip</code> wallet from the <a href="https://github.com/XDagger/xdag/releases" target="_blank">official repository</a>.</li>
							<li>Extract the archive into <code>C:\DaggerGpuMiner\wallet</code></li>
							<li>Create a new bat file <code>C:\DaggerGpuMiner\wallet\RUNWALLET.bat</code></li>
							<li>Edit with notepad. Insert one line into the file: <pre class="oneline">C:\DaggerGpuMiner\wallet\xdag.exe -d -m 1 {{ Setting::get('pool_domain') }}:{{ Setting::get('pool_port') }}</pre>
							<li>Run the <code>RUNWALLET.bat</code> file.</li>
							<li>Set your wallet password, type random keys (at least 3 lines of random keys). Wait until the host keys are generated (might take a while).</li>
							<li>Type <code>state</code> and press enter. If the output is not <code>Connected to the mainnet pool. Mining on. Normal operation.</code>, wait a bit and type <code>state</code> followed by enter key again.</li>
							<li>Type <code>account</code>. You will see your XDAG address. Select it using mouse, and press ENTER to copy it into your clipboard.</li>
							<li>Open a new notepad instance, and paste your XDAG wallet address there.</li>
							<li>Go back to the open CPU miner console, and enter <code>terminate</code>, followed by enter key.</li>
							<li>Create a new bat file <code>C:\DaggerGpuMiner\RUNMINER.bat</code></li>
							<li>Edit with notepad. Insert one line into the file: <pre class="oneline">C:\DaggerGpuMiner\DaggerGpuMiner.exe -G -a <span class="parameter">wallet_address</span> -p {{ Setting::get('pool_domain') }}:{{ Setting::get('pool_port') }} -t 0 -v 2 -opencl-platform <span class="parameter">platform_id</span> -opencl-devices <span class="parameter">device_nums</span></pre> Replace <span class="parameter">wallet_address</span> with the address you copied into notepad. Replace <span class="parameter">platform_id</span> with your OpenCL platform ID, this is most usually <code>0</code> (also try <code>1</code> or <code>2</code> if necessary). If you have more than one GPU in the system, count up from zero to (number of devices - 1), so for example if you have 4 GPUs in your system, replace <span class="parameter">device_nums</span> with <code>0 1 2 3</code>. If you have only one GPU, replace <span class="parameter">device_nums</span> with <code>0</code>. To see advanced GPU miner parameters, execute <code>DaggerGpuMiner.exe -h</code>.</li>
							<li>Double click the <code>RUNMINER.bat</code> file, your miner will now start. Do not close the console window at any time.</li>
						</ol>
						<p>Done! For usage, see the next usage section.</p>
						<p><span class="important">Note:</span> if you are using NVIDIA GPUs, make sure you add <code>-nvidia-fix</code> at the end of the command line in step 14 to prevent high system CPU usage and increase your hashrate.</p>
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
							<li>To update your installation to the latest version, stop the miner by pressing <code>CTRL+C</code> in the miner console. Download new <code>x64</code> zip package from <a href="https://github.com/jonano614/DaggerGpuMiner/releases" target="_blank">GitHub</a>. Extract the archive into <code>C:\DaggerGpuMiner</code>, overwriting the files. Run <code>RUNMINER.bat</code> as usual.</li>
							<li>To view your current balance at any time, execute <code>RUNWALLET.bat</code>, type in your wallet password, press enter, type <code>balance</code> and press enter. If you see <code>not ready to show balance</code>, wait a bit and type <code>balance</code> again, followed by enter key. When you are done, type <code>terminate</code> followed by enter to close your wallet. You can also use our website to check your balance at any time on the home page, or <a href="{{ route('register') }}">register</a> your miner to automatically show it's balance, payouts, unpaid shares and more.</li>
						</ol>
					</div>
				</div>
			</nav>
		</div>
	</div>
@endsection
