@extends('layouts.app')

@section('title')
	Miners
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Miners
				</h1>
				<h2 class="subtitle">
					Manage your miners easily.
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('content')
	<div class="miners-view">
		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<form action="{{ route('miners.alerts') }}" method="post">
					{{ csrf_field() }}
					<table class="table is-fullwidth miners-list">
						<thead>
							<tr>
								<th>Miner address</th>
								<th class="tooltip" data-tooltip="Status updates every 5 minutes.">Status</th>
								<th class="tooltip" data-tooltip="Estimated hashrate. Updates every 5 minutes.">Hashrate</th>
								<th class="tooltip" data-tooltip="Unpaid shares. Updates every 5 minutes.">Unpaid shares</th>
								<th class="tooltip" data-tooltip="Current address balance. Updates every 4 hours.">Balance</th>
								<th class="tooltip is-tooltip-multiline" data-tooltip="E-mail alerts when miner goes offline. Currently under development.">Alerts</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@forelse ($authUser->miners as $miner)
								<tr class="miner" data-uuid="{{ $miner->uuid }}" data-address="{{ $miner->address }}" data-note="{{ $miner->address }}">
									<td class="miner-address{{ $miner->note != '' ? ' tooltip' : '' }} is-tooltip-multiline" data-tooltip="{{ $miner->note }}">{{ $miner->address }}</td>
									<td class="miner-status api is-loading"></td>
									<td class="miner-hashrate api is-loading"></td>
									<td class="miner-nopaid-shares api is-loading"></td>
									<td class="miner-balance api is-loading"></td>
									<td>
										<input type="hidden" name="alerts[{{ $miner->uuid }}]" value="0">
										<input type="checkbox" name="alerts[{{ $miner->uuid }}]" value="1"{{ $miner->email_alerts ? ' checked' : '' }}>
									</td>
									<td>
										<a class="button is-danger delete-miner" href="#">
											<span class="icon"><i class="fa fa-trash-o"></i></span>
										</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="7">No miners.</td>
								</tr>
							@endforelse
						</tbody>
					</table>

					<button type="submit" class="button is-pulled-right">
						<span class="icon"><i class="fa fa-floppy-o"></i></span>
						<span>Save alert preferences</span>
					</button>

					<a class="button is-primary" id="addMiner">
						<span class="icon"><i class="fa fa-plus-square-o"></i></span>
						<span>Add miner</span>
					</a>
				</form>
			</div>
		</div>

		<div class="modal" id="addMinerModal">
			<div class="modal-background"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title">Add miner</p>
					<a class="delete close-modal" aria-label="close" href="#"></a>
				</header>
				<form id="addMinerForm" method="post" action="{{ route('miners.create') }}">
					{{ csrf_field() }}
					<section class="modal-card-body">
						<p>You can find your miner address by typing <code>account</code> in miner console.</p>

						<div class="column">
							<div class="field is-horizontal">
								<div class="field-label">
									<label class="label">Address</label>
								</div>

								<div class="field-body">
									<div class="field">
										<p class="control has-icons-left has-icons-right">
											<input class="input" type="text" id="address" name="address" maxlength="32" required>
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
									<label class="label">Note</label>
								</div>

								<div class="field-body">
									<div class="field">
										<p class="control has-icons-left has-icons-right">
											<input class="input" type="text" id="note" name="note">
											<span class="icon is-small is-left">
												<i class="fa fa-sticky-note-o"></i>
											</span>
										</p>
									</div>
								</div>
							</div>
						</div>
					</section>
					<footer class="modal-card-foot">
						<button type="submit" class="button is-success">Save</button>
					</footer>
				</form>
			</div>
		</div>

		<div class="modal" id="deleteMinerModal">
			<div class="modal-background"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title">Delete miner</p>
					<a class="delete close-modal" aria-label="close" href="#"></a>
				</header>
				<form id="deleteMinerForm" method="post" action="{{ route('miners.delete') }}">
					<input type="hidden" name="_method" value="delete">
					{{ csrf_field() }}
					<section class="modal-card-body">
						<p>Are you sure you want to delete this miner from your list?</p>

						<div class="column">
							<div class="field is-horizontal">
								<div class="field-label">
									<label class="label">Address</label>
								</div>

								<div class="field-body">
									<div class="field">
										<p class="control has-icons-left has-icons-right">
											<input class="input" type="text" id="deleteMinerAddress" name="address" readonly>
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
									<label class="label">Note</label>
								</div>

								<div class="field-body">
									<div class="field">
										<p class="control has-icons-left has-icons-right">
											<input class="input" type="text" id="deleteMinerNote" name="note" readonly>
											<span class="icon is-small is-left">
												<i class="fa fa-sticky-note-o"></i>
											</span>
										</p>
									</div>
								</div>
							</div>
						</div>
					</section>
					<footer class="modal-card-foot">
						<button type="submit" class="button is-danger">Delete</button>
						<button type="button" class="button close-modal">Back</button>
					</footer>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		var minersView = new minersView();
	</script>
@endsection
