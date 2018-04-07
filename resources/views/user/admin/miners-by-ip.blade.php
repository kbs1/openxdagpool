@extends('layouts.admin')

@section('title')
	Miners by IP
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Miners by IP
				</h1>
				<h2 class="subtitle">
					All pool miners grouped by IP address
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('adminContent')
	<table class="miners-grouped table is-fullwidth">
		<thead>
			<tr>
				<th>IP address</th>
				<th class="tooltip is-tooltip-multiline" data-tooltip="Miners connected from this IP, their addresses and ports.">Miners</th>
				<th class="tooltip is-tooltip-multiline" data-tooltip="Current estimated hashrate. The value is not averaged.">Hashrate</th>
				<th class="tooltip is-tooltip-multiline" data-tooltip="Registered users connected from this IP, and their miner addresses.">Users</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($ips as $ip => $data)
				<tr>
					<td>{{ $ip }}</td>
					<td class="tooltip is-tooltip-multiline" data-tooltip="@foreach ($data as $key => $miner)
						@if ($key == 'machines' || $key == 'unpaid_shares')
							@continue
						@endif
						{{ $miner->getAddress() }}:
						{{ $miner->getIpsAndPort() }}
					@endforeach">{{ $data['machines'] }}</td>
					<td>
						@if ($pool_unpaid_shares == 0)
							-
						@else
							{{ $format->hashrate(($data['unpaid_shares'] / $pool_unpaid_shares) * $pool_hashrate) }}
						@endif
					</td>
					<td>
						@php($users = [])
						@foreach ($data as $key => $miner)
							@if ($key == 'machines' || $key == 'unpaid_shares')
								@continue
							@endif
							@php($users = array_merge($users, $miner->getUsers()))
						@endforeach
						@forelse ($users as $user)
							<a href="{{ route('user.admin.edit-user', $user->id) }}">{{ $user->nick }}</a>@if (!$loop->last), @endif
						@empty
							-
						@endforelse
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	{{ $ips->links() }}
@endsection
