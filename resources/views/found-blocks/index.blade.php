@extends('layouts.app')

@section('title')
	Found blocks
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Found blocks
				</h1>
				<h2 class="subtitle">
					Last 150 blocks mined by this pool
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
						Found blocks
					</p>
				</header>

				<div class="card-content">
					<div class="content">
						<p><span class="important">Note:</span> This list updates every 4 hours.</p>

						<table class="table is-fullwidth">
							<thead>
								<tr>
									<th class="tooltip" data-tooltip="Block payout was fully sent at this date and time.">Found at</th>
									<th class="tooltip" data-tooltip="Found block's hash.">Hash</th>
									<th class="tooltip" data-tooltip="Total payout given to miners and community fund.">Payout</th>
									<th class="tooltip" data-tooltip="Pool's fee for this block.">Fee</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($blocks as $block)
									<tr>
										<td>{{ $block->found_at->format('Y-m-d H:i:s') }}.{{ sprintf('%03d', $block->found_at_milliseconds) }}</td>
										<td>
											<a href="#" class="tooltip is-tooltip-multiline found-block-details" data-tooltip="{{ $block->hash }}" data-found-at="{{ $block->found_at->format('Y-m-d H:i:s') }}.{{ sprintf('%03d', $block->found_at_milliseconds) }}" data-payout="{{ $block->payout }}" data-fee="{{ $block->fee }}" data-res="{{ $block->res }}" data-t="{{ $block->t }}">
												{{ $block->short_hash }}
											</a>
										</td>
										<td>{{ number_format($block->payout, 2, '.', ',') }} XDAG</td>
										<td>{{ number_format($block->fee, 2, '.', ',') }} XDAG</td>
									</tr>
								@empty
									<tr>
										<td colspan="4">No found blocks yet, please come back later! ;-)</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</nav>
		</div>
	</div>

	<div class="modal" id="foundBlockDetailsModal">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">Found block details</p>
				<a class="delete close-modal" aria-label="close" href="#"></a>
			</header>
			<section class="modal-card-body">
				<div class="column">
					<div class="field is-horizontal">
						<div class="field-label">
							<label class="label">Found at</label>
						</div>

						<div class="field-body">
							<div class="field">
								<p class="control has-icons-left">
									<input class="input is-disabled" type="text" name="found_at" readonly>
									<span class="icon is-small is-left">
										<i class="fa fa-calendar"></i>
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

				<div class="column">
					<div class="field is-horizontal">
						<div class="field-label">
							<label class="label">Hash</label>
						</div>

						<div class="field-body">
							<div class="field">
								<p class="control has-icons-left">
									<input class="input is-disabled" type="text" name="hash" readonly>
									<span class="icon is-small is-left">
										<i class="fa fa-id-card-o"></i>
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

				<div class="column">
					<div class="field is-horizontal">
						<div class="field-label">
							<label class="label">t</label>
						</div>

						<div class="field-body">
							<div class="field">
								<p class="control has-icons-left">
									<input class="input is-disabled" type="text" name="t" readonly>
									<span class="icon is-small is-left">
										<i class="fa fa-bars"></i>
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>

				<div class="column">
					<div class="field is-horizontal">
						<div class="field-label">
							<label class="label">res</label>
						</div>

						<div class="field-body">
							<div class="field">
								<p class="control has-icons-left">
									<input class="input is-disabled" type="text" name="res" readonly>
									<span class="icon is-small is-left">
										<i class="fa fa-check"></i>
									</span>
								</p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<footer class="modal-card-foot">
				<a href="#" class="button is-primary">Open in block explorer</a>
				<button type="button" class="button close-modal">Close</button>
			</footer>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		var foundBlocksView = new foundBlocksView();
	</script>
@endsection
