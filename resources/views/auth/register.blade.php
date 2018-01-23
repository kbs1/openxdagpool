@extends('layouts.app')

@section('title')
	Register
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Register
				</h1>
				<h2 class="subtitle">
					Register if you want to easily keep track of your miners, their hashrates, balances, payouts and receive email notifications should your miner go offline.
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('content')
	<div class="columns is-marginless is-centered">
		<div class="column is-5">
			<div class="card">
				<header class="card-header">
					<p class="card-header-title">Register</p>
				</header>

				<div class="card-content">
					<form class="register-form" method="POST" action="{{ route('register') }}">

						{{ csrf_field() }}

						<div class="field is-horizontal">
							<div class="field-label">
								<label class="label">Nick</label>
							</div>

							<div class="field-body">
								<div class="field">
									<p class="control">
										<input class="input" id="nick" type="text" name="nick" value="{{ old('nick') }}" maxlength="20" required autofocus>
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
							<div class="field-label">
								<label class="label">E-mail</label>
							</div>

							<div class="field-body">
								<div class="field">
									<p class="control">
										<input class="input" id="email" type="email" name="email" value="{{ old('email') }}" maxlength="255" required autofocus>
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
							<div class="field-label">
								<label class="label">Password</label>
							</div>

							<div class="field-body">
								<div class="field">
									<p class="control">
										<input class="input" id="password" type="password" name="password" required>
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
										<input class="input" id="password-confirm" type="password" name="password_confirmation" required>
									</p>
								</div>
							</div>
						</div>

						<div class="field is-horizontal">
							<div class="field-label"></div>

							<div class="field-body">
								<div class="field is-grouped">
									<div class="control">
										<button type="submit" class="button is-primary">Register</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
