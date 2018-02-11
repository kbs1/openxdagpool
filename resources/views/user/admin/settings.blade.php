@extends('layouts.admin')

@section('title')
	Settings
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Settings
				</h1>
				<h2 class="subtitle">
					Pool website settings and various information
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('adminContent')
	<nav class="card">
		<header class="card-header">
			<p class="card-header-title">
				Settings
			</p>
		</header>

		<div class="card-content">
			<form method="POST" action="{{ route('user.admin.settings.save') }}">

				{{ csrf_field() }}

				<div class="field is-horizontal">
					<p>Pool fee and related settings. Always keep this in sync with your pool daemon.</p>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Pool fee</label>
					</div>

					<div class="field-body">
						<div class="field has-addons">
							<p class="control">
								<input class="input" type="number" min="0.00" max="100.00" step="0.01" name="fees_percent" value="{{ old('fees_percent', Setting::get('fees_percent', 0.5)) }}" maxlength="6" required>
							</p>
							<p class="control">
								<a class="button is-static">
									<span class="fa fa-percent"></span>
								</a>
							</p>

							@if ($errors->has('fees_percent'))
								<p class="help is-danger">
									{{ $errors->first('fees_percent') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal tootip" data-tooltip="Reward for found block">
					<div class="field-label">
						<label class="label">Block reward</label>
					</div>

					<div class="field-body">
						<div class="field has-addons">
							<p class="control">
								<input class="input" type="number" min="0.00" max="100.00" step="0.01" name="reward_percent" value="{{ old('reward_percent', Setting::get('reward_percent', 1)) }}" maxlength="6" required>
							</p>
							<p class="control">
								<a class="button is-static">
									<span class="fa fa-percent"></span>
								</a>
							</p>

							@if ($errors->has('reward_percent'))
								<p class="help is-danger">
									{{ $errors->first('reward_percent') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal tootip is-tooltip-multiline" data-tooltip="Reward for direct contributions to found block">
					<div class="field-label">
						<label class="label">Direct percent</label>
					</div>

					<div class="field-body">
						<div class="field has-addons">
							<p class="control">
								<input class="input" type="number" min="0.00" max="100.00" step="0.01" name="direct_percent" value="{{ old('direct_percent', Setting::get('direct_percent', 1)) }}" maxlength="6" required>
							</p>
							<p class="control">
								<a class="button is-static">
									<span class="fa fa-percent"></span>
								</a>
							</p>

							@if ($errors->has('direct_percent'))
								<p class="help is-danger">
									{{ $errors->first('direct_percent') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal tootip" data-tooltip="Donation to community fund">
					<div class="field-label">
						<label class="label">Community fund</label>
					</div>

					<div class="field-body">
						<div class="field has-addons">
							<p class="control">
								<input class="input" type="number" min="0.00" max="100.00" step="0.01" name="fund_percent" value="{{ old('fund_percent', Setting::get('fund_percent', 0.5)) }}" maxlength="6" required>
							</p>
							<p class="control">
								<a class="button is-static">
									<span class="fa fa-percent"></span>
								</a>
							</p>

							@if ($errors->has('fund_percent'))
								<p class="help is-danger">
									{{ $errors->first('fund_percent') }}
								</p>
							@endif
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
