@extends('layouts.app')

@section('content')
	<div class="admin-view">
		<div class="columns is-marginless is-centered">
			<div class="column is-7">
				<div class="tabs">
					<ul>
						<li{!! $section == 'users' ? ' class="is-active"' : '' !!}><a href="{{ route('user.admin.users') }}">Users</a></li>
						<li{!! $section == 'settings' ? ' class="is-active"' : '' !!}><a href="{{ route('user.admin.users') }}">Settings</a></li>
					</ul>
				</div>

				@yield('adminContent')
			</div>
		</div>
	</div>
@endsection
