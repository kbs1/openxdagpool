@extends('layouts.app')

@section('title')
	Leaderboard
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Leaderboard
				</h1>
				<h2 class="subtitle">
					Registered users contributing the most hashrate
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('content')
	<div class="leaderboard-view">
		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				@if (isset($authUser) && $authUser->isActive())
					<div class="notification is-info">
						<button class="delete"></button>
						@if ($authUser->exclude_from_leaderboard)
							You are currently excluded from the leaderboard. Visit your <a href="{{ route('profile') }}">user profile</a> to change this setting.
						@else
							@if ($authUser->anonymous_profile)
								Your nick is currently hidden from other users, only you can see it. You can verify this by logging out. Visit your <a href="{{ route('profile') }}">user profile</a> to change this setting.
							@else
								Your nick is currently shown to all visitors. Visit your <a href="{{ route('profile') }}">user profile</a> to change this setting.
							@endif
						@endif
					</div>
				@endif

				@if (isset($authUser) && $authUser->isAdministrator())
					<div class="notification is-info">
						<button class="delete"></button>
						You are an administrator, all user nicks and all users are visible. Log out to see the leaderboard as other users.
					</div>
				@endif

				<table class="table is-fullwidth is-striped">
					<thead>
						<tr>
							<th>Rank</th>
							<th>Nick</th>
							<th class="tooltip is-tooltip-multiline" data-tooltip="Current hashrate of all user's registered miners. Updates every 5 minutes.">Hashrate</th>
						</tr>
					</thead>
					<tbody>
						@php ($shown_full = $shown_myself = false)
						@php ($myself = $myself_rank = $myself_hashrate = null)
						@forelse ($leaderboard as $index => $item)
							@if ($index > 24)
								@if (isset($authUser) && $item['user']->id === $authUser->id)
									@php ($myself = $item['user'])
									@php ($myself_rank = $index + 1)
									@php ($myself_hashrate = $item['hashrate'])
								@endif
								@if (!$shown_full && !$shown_myself && isset($authUser) && (!$authUser->exclude_from_leaderboard || $authUser->isAdministrator()))
									@php ($shown_full = true)
									<tr>
										<td>...</td>
										<td colspan="2"></td>
									</tr>
								@endif
							@else
								@if (!$shown_myself && isset($authUser) && $item['user']->id === $authUser->id)
									@php ($shown_myself = true)
								@endif
								<tr{!! isset($authUser) && $item['user']->id === $authUser->id ? ' class="is-selected"' : '' !!}>
									<th>#{{ $loop->iteration }}</th>
									<td>{{ $item['user']->display_nick }}</td>
									<td>{{ $item['hashrate'] }}</td>
								</tr>
							@endif
						@empty
							<tr>
								<td colspan="3">No users to show, please check back later! ;-)</td>
							</tr>
						@endforelse
						@if ($myself)
							<tr class="is-selected">
								<th>#{{ $myself_rank }}</th>
								<td>{{ $myself->display_nick }}</td>
								<td>{{ $myself_hashrate }}</td>
							</tr>
						@endif
					</tbody>
				</table>

				<hr>
				<p><span class="important">Note:</span> Hash rates update every 5 minutes. Hash rate calculation is purely informational, it does not represent 'what the pool sees', or user's real mining speed. It is a statistical approximation, displayed for informational purposes only. The reading should start matching user's real speed over a longer period of time (usually 6 hours). Displayed hash rate does not affect payouts at all.</p>
			</div>
		</div>
	</div>
@endsection
