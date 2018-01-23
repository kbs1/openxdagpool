@extends('layouts.app')

@section('title')
	Payments ({{ $miner->short_address }})
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Payment history for
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
				<table class="table is-fullwidth miners-list">
					<thead>
						<tr>
							<th>Date and time</th>
							<th>Sender</th>
							<th>Recipient</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($miner->payments as $payment)
							<tr>
								<td class="tooltip" data-tooltip="{{ $payment->made_at_full }}">{{ $payment->made_at->format('Y-m-d H:i:s') }}</td>
								<td>{{ $payment->sender }}</td>
								<td>{{ $miner->address }}</td>
								<td>{{ number_format($payment->amount, 9, '.', ',') }} XDAG</td>
							</tr>
						@empty
							<tr>
								<td colspan="4">No payments for address <strong>{{ $miner->address }}</strong> yet, check back later! ;-)</td>
							</tr>
						@endforelse
						@if ($miner->payments->count())
							<tr>
								<td colspan="3">TOTAL:</td>
								<td>{{ number_format($miner->payments->sum('amount'), 9, '.', ',') }}</td>
							</tr>
						@endif
					</tbody>
				</table>

				<a class="button is-primary is-pulled-right" href="" target="_blank">
					<span class="icon"><i class="fa fa-file-excel-o"></i></span>
					<span>Export</span>
				</a>

				<a class="button" href="{{ route('miners') }}">
					<span>Back</span>
				</a>
				<hr>
				<p><span class="important">Note:</span> Payment history updates once a hour. Payments are sent immediately after they are mined by the pool.</p>
			</div>
		</div>
	</div>
@endsection
