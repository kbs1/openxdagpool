@extends('layouts.app')

@section('title')
	{{ Auth::user()->nick }}
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					{{ Auth::user()->nick }}
				</h1>
				<h2 class="subtitle">
					Update your profile
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
						Update profile
					</p>
				</header>

				<div class="card-content">
					<form class="register-form" method="POST" action="{{ route('profile.update') }}">

						{{ csrf_field() }}

						<div class="field is-horizontal">
							<div class="field-label">
								<label class="label">Nick</label>
							</div>

							<div class="field-body">
								<div class="field">
									<p class="control">
										<input class="input" id="nick" type="text" name="nick" value="{{ old('nick', $authUser->nick) }}" maxlength="20" required autofocus>
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
								<label class="checkbox tooltip" data-tooltip="When checked, your nick is never displayed to other users.">
									<input type="hidden" name="anonymous_profile" value="0">
									<input type="checkbox" name="anonymous_profile" value="1"{{ $authUser->anonymous_profile ? ' checked' : '' }}>
									anonymous profile
								</label>
							</div>
						</div>

						<div class="field is-horizontal">
							<div class="field-label"></div>
							<div class="field-body">
								<label class="checkbox tooltip is-tooltip-multiline" data-tooltip="When checked, your hashrate is never displayed on the leaderboard.">
									<input type="hidden" name="exclude_from_leaderboard" value="0">
									<input type="checkbox" name="exclude_from_leaderboard" value="1"{{ $authUser->exclude_from_leaderboard ? ' checked' : '' }}>
									exclude from leaderboard
								</label>
							</div>
						</div>

						<div class="field is-horizontal">
							<div class="field-label">
								<label class="label">E-mail</label>
							</div>

							<div class="field-body">
								<div class="field">
									<p class="control tooltip" data-tooltip="Your e-mail is never displayed to anyone.">
										<input class="input" id="email" type="email" name="email" value="{{ old('email', $authUser->email) }}" maxlength="255" required autofocus>
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
								<p class="help">Do not type passwords if you don't want to change your current password.</p>
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
		</div>
	</div>
@endsection
