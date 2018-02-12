@extends('layouts.app')

@section('flashMessagesWidth', '8')

@section('content')
	<div class="admin-view">
		<div class="columns is-marginless is-centered">
			<div class="column is-8">
				<div class="tabs">
					<ul>
						<li{!! $section == 'users' ? ' class="is-active"' : '' !!}><a href="{{ route('user.admin.users') }}">Users</a></li>
						<li{!! $section == 'settings' ? ' class="is-active"' : '' !!}><a href="{{ route('user.admin.settings') }}">Settings</a></li>
					</ul>
				</div>

				@yield('adminContent')
			</div>
		</div>
	</div>
@endsection
