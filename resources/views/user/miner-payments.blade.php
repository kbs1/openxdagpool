@extends('layouts.app')

@section('title')
	Payments ({{ $miner->short_address }})
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Payment history
				</h1>
				<h2 class="subtitle">
					Coins earned at this pool
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('content')
	<div class="miners-view">
		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<h4 class="title is-4">Address {{ $miner->address }}</h4>
				@include('user.partials.payments')

				<a class="button is-primary is-pulled-right" href="{{ route('miners.payments.export', urlencode($miner->address)) }}" target="_blank">
					<span class="icon"><i class="fa fa-file-excel-o"></i></span>
					<span>Export</span>
				</a>

				<a class="button" href="{{ route('miners') }}">
					<span>Back</span>
				</a>
				<hr>
				<p><span class="important">Note:</span> Payments update approximately every 4 hours. Payments are sent immediately after they are ready.</p>
			</div>
		</div>
	</div>
@endsection
