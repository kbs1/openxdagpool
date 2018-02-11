@extends('layouts.admin')

@section('title')
	{{ $user->nick }}'s profile
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					{{ $user->nick }}'s profile
				</h1>
				<h2 class="subtitle">
					Update user profile below
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('adminContent')
	<nav class="card">
		<header class="card-header">
			<p class="card-header-title">
				Update profile
			</p>
		</header>

		<div class="card-content">
			<form method="POST" action="{{ route('user.admin.update-user', $user->id) }}">

				{{ csrf_field() }}
				<input type="hidden" name="id" value="{{ $user->id }}">

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Nick</label>
					</div>

					<div class="field-body">
						<div class="field">
							<p class="control">
								<input class="input" id="nick" type="text" name="nick" value="{{ old('nick', $user->nick) }}" maxlength="20" required autofocus>
							</p>

							@if ($errors->has('nick'))
								<p class="help is-danger">
									{{ $errors->first('nick') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label"></div>
					<div class="field-body">
						<label class="checkbox tooltip" data-tooltip="When checked, user's nick is never displayed to other users.">
							<input type="hidden" name="anonymous_profile" value="0">
							<input type="checkbox" name="anonymous_profile" value="1"{{ $user->anonymous_profile ? ' checked' : '' }}>
							anonymous profile
						</label>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label"></div>
					<div class="field-body">
						<label class="checkbox tooltip is-tooltip-multiline" data-tooltip="When checked, user's hashrate is never displayed on the leaderboard.">
							<input type="hidden" name="exclude_from_leaderboard" value="0">
							<input type="checkbox" name="exclude_from_leaderboard" value="1"{{ $user->exclude_from_leaderboard ? ' checked' : '' }}>
							exclude from leaderboard
						</label>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label"></div>
					<div class="field-body">
						<label class="checkbox tooltip is-tooltip-multiline" data-tooltip="When checked, user won't be able to log in or access his user profile.">
							<input type="hidden" name="active" value="1">
							<input type="checkbox" name="active" value="0"{{ !$user->active ? ' checked' : '' }}>
							locked
						</label>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label"></div>
					<div class="field-body">
						<label class="checkbox tooltip is-tooltip-multiline" data-tooltip="When checked, user will have administrative rights.">
							<input type="hidden" name="administrator" value="0">
							<input type="checkbox" name="administrator" value="1"{{ $user->administrator ? ' checked' : '' }}>
							administrator
						</label>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">E-mail</label>
					</div>

					<div class="field-body">
						<div class="field">
							<p class="control tooltip" data-tooltip="User's e-mail is never displayed to anyone.">
								<input class="input" id="email" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" required autofocus>
							</p>

							@if ($errors->has('email'))
								<p class="help is-danger">
									{{ $errors->first('email') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label"></div>
					<div class="field-body">
						<p class="help">Do not type passwords if you don't want to change user's password.</p>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Password</label>
					</div>

					<div class="field-body">
						<div class="field">
							<p class="control">
								<input class="input" id="password" type="password" name="password">
							</p>

							@if ($errors->has('password'))
								<p class="help is-danger">
									{{ $errors->first('password') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Confirm password</label>
					</div>

					<div class="field-body">
						<div class="field">
							<p class="control">
								<input class="input" id="password-confirm" type="password" name="password_confirmation">
							</p>
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label"></div>

					<div class="field-body">
						<div class="field is-grouped">
							<div class="control">
								<button type="submit" class="button is-primary">Save</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</nav>
@endsection
