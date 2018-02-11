@extends('layouts.admin')

@section('title')
	Users
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Users
				</h1>
				<h2 class="subtitle">
					All registered pool users
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('adminContent')
	<table class="table is-fullwidth">
		<thead>
			<tr>
				<th>Nick</th>
				<th>E-mail</th>
				<th class="tooltip" data-tooltip="Locked users can't log in.">Locked</th>
				<th>Administrator</th>
				<th>Miners</th>
				<th>Hashrate</th>
				<th>Last seen</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)
				<tr>
					<td>{{ $user->nick }}</td>
					<td>{{ $user->email }}</td>
					<td class="tooltip" data-tooltip="Locked users can't log in.">{{ $user->isActive() ? 'No' : 'Yes' }}</td>
					<td>{{ $user->isAdministrator() ? 'Yes' : 'No' }}</td>
					<td>{{ $user->miners()->count() }}</td>
					<td>{{ $format->hashrate($user->getHashrateSum()) }}</td>
					<td>{{ $user->last_seen_at->format('Y-m-d H:i:s') }}</td>
					<td>
						<a class="button tooltip" href="{{ route('user.admin.edit-user', $user->id) }}" data-tooltip="Edit user">
							<span class="icon"><i class="fa fa-pencil"></i></span>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	{{ $users->links() }}
@endsection
