@extends('layouts.admin')

@section('title')
	Miners by hashrate
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Miners by hashrate
				</h1>
				<h2 class="subtitle">
					All pool miners grouped by hashrate
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('adminContent')
	<table class="miners-grouped table is-fullwidth">
		<thead>
			<tr>
				<th>Miner address</th>
				<th>Machines</th>
				<th class="tooltip is-tooltip-multiline" data-tooltip="Current estimated hashrate. The value is not averaged.">Hashrate</th>
				<th class="tooltip is-tooltip-multiline" data-tooltip="Miner address is registered to these user accounts.">Users</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($miners as $miner)
				<tr>
					<td><a href="#" class="miner-details">{{ $miner->getAddress() }}</a></td>
					<td class="tooltip is-tooltip-multiline is-tooltip-right ips-and-port" data-tooltip="{{ $miner->getIpsAndPort() }}">{{ $miner->getMachinesCount() }}</td>
					<td>{{ $format->hashrate($miner->getHashrate()) }}</td>
					<td>
						@forelse ($miner->getUsers() as $user)
							<a href="{{ route('user.admin.edit-user', $user->id) }}">{{ $user->nick }}</a>@if (!$loop->last), @endif
						@empty
							-
						@endforelse
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="modal" id="minerDetailsModal">
			<div class="modal-background"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title">Miner details</p>
					<a class="delete close-modal" aria-label="close" href="#"></a>
				</header>
				<section class="modal-card-body">
					<div class="column">
						<div class="field is-horizontal">
							<div class="field-label">
								<label class="label">Address</label>
							</div>

							<div class="field-body">
								<div class="field">
									<p class="control has-icons-left has-icons-right">
										<input class="input is-disabled" type="text" name="address" readonly>
										<span class="icon is-small is-left">
											<i class="fa fa-address-card-o"></i>
										</span>
									</p>
								</div>
							</div>
						</div>
					</div>

					<div class="column">
						<div class="field is-horizontal">
							<div class="field-label">
								<label class="label">Machines</label>
							</div>

							<div class="field-body">
								<div class="field">
									<textarea class="textarea is-disabled" name="ips_and_port" rows="8" readonly></textarea>
								</div>
							</div>
						</div>
					</div>
				</section>
				<footer class="modal-card-foot">
					<button type="button" class="button close-modal">Close</button>
				</footer>
			</div>
		</div>
	</div>

	{{ $miners->links() }}
@endsection

@section('scripts')
	<script>
		var adminMinersByHashrateView = new adminMinersByHashrateView();
	</script>
@endsection
