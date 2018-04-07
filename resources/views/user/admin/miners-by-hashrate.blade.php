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
					<td>{{ $miner->getAddress() }}</td>
					<td class="tooltip is-tooltip-multiline" data-tooltip="{{ $miner->getIpsAndPort() }}">{{ $miner->getMachinesCount() }}</td>
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

	{{ $miners->links() }}
@endsection
