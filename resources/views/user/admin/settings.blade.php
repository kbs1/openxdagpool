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
					<p>Pool and website settings.</p>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Pool name</label>
					</div>

					<div class="field-body">
						<div class="field tooltip is-tooltip-multiline" data-tooltip="Name of your pool, presented on various places on the website.">
							<p class="control">
								<input class="input" type="text" name="pool_name" value="{{ old('pool_name', Setting::get('pool_name', 'OpenXDAGPool')) }}" required>
							</p>

							@if ($errors->has('pool_name'))
								<p class="help is-danger">
									{{ $errors->first('pool_name') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Pool tagline</label>
					</div>

					<div class="field-body">
						<div class="field tooltip" data-tooltip="Tagline on homepage. Describes your pool.">
							<p class="control">
								<input class="input" type="text" name="pool_tagline" value="{{ old('pool_tagline', Setting::get('pool_tagline', 'Reliable mining pool')) }}" required>
							</p>

							@if ($errors->has('pool_tagline'))
								<p class="help is-danger">
									{{ $errors->first('pool_tagline') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Pool tooltip</label>
					</div>

					<div class="field-body">
						<div class="field tooltip" data-tooltip="Tooltip when users hover over the tagline. Shows additional info, for example location or server settings.">
							<p class="control">
								<input class="input" type="text" name="pool_tooltip" value="{{ old('pool_tooltip', Setting::get('pool_tooltip', '1Gbit/s connection')) }}" required>
							</p>

							@if ($errors->has('pool_tooltip'))
								<p class="help is-danger">
									{{ $errors->first('pool_tooltip') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Pool domain name</label>
					</div>

					<div class="field-body">
						<div class="field tooltip is-tooltip-multiline" data-tooltip="DNS domain name of your pool. This is not necessarily the same as website DNS domain name, you can enter for example pool.mypool.com, which would allow you to move the pool independently from the website. This DNS domain name will be used in all miner setup guides.">
							<p class="control">
								<input class="input" type="text" name="pool_domain" value="{{ old('pool_domain', Setting::get('pool_domain', 'pool.mypool.com')) }}" required>
							</p>

							@if ($errors->has('pool_domain'))
								<p class="help is-danger">
									{{ $errors->first('pool_domain') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Pool port</label>
					</div>

					<div class="field-body">
						<div class="field tooltip" data-tooltip="Public port the pool is listening on.">
							<p class="control">
								<input class="input" type="number" min="1" max="65535" step="1" name="pool_port" value="{{ old('pool_port', Setting::get('pool_port', '13654')) }}" required>
							</p>

							@if ($errors->has('pool_port'))
								<p class="help is-danger">
									{{ $errors->first('pool_port') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Website domain name</label>
					</div>

					<div class="field-body">
						<div class="field tooltip" data-tooltip="DNS domain name of your website.">
							<p class="control">
								<input class="input" type="text" name="website_domain" value="{{ old('website_domain', Setting::get('website_domain', 'mypool.com')) }}" required>
							</p>

							@if ($errors->has('website_domain'))
								<p class="help is-danger">
									{{ $errors->first('website_domain') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Contact e-mail</label>
					</div>

					<div class="field-body">
						<div class="field tooltip is-tooltip-multiline" data-tooltip="Contact e-mail presented when user click link in the footer.">
							<p class="control">
								<input class="input" type="email" name="contact_email" value="{{ old('contact_email', Setting::get('contact_email', 'admin@mypoool.com')) }}" required>
							</p>

							@if ($errors->has('contact_email'))
								<p class="help is-danger">
									{{ $errors->first('contact_email') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Important message</label>
					</div>

					<div class="field-body">
						<div class="field tooltip is-tooltip-multiline" data-tooltip="Important message shown on top of the home page. Leave empty for no message. HTML allowed.">
							<p class="control">
								<textarea class="textarea" rows="4" name="important_message_html">{{ old('important_message_html', Setting::get('important_message_html')) }}</textarea>
							</p>

							@if ($errors->has('important_message_html'))
								<p class="help is-danger">
									{{ $errors->first('important_message_html') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Show message until</label>
					</div>

					<div class="field-body">
						<div class="field tooltip is-tooltip-multiline" data-tooltip="Important message won't show past end of selected date. If no date is selected, it will display forever.">
							<p class="control">
								<input class="input" type="text" id="important_message_until" name="important_message_until" value="{{ old('important_message_until', Setting::get('important_message_until')) }}">
							</p>

							@if ($errors->has('important_message_until'))
								<p class="help is-danger">
									{{ $errors->first('important_message_until') }}
								</p>
							@endif
						</div>
					</div>
				</div>

				<div class="field is-horizontal">
					<div class="field-label">
						<label class="label">Pool news</label>
					</div>

					<div class="field-body">
						<div class="field tooltip is-tooltip-multiline" data-tooltip="Pool news at the bottom of the homepage. HTML allowed.">
							<p class="control">
								<textarea class="textarea" rows="4" name="pool_news_html">{{ old('pool_news_html', Setting::get('pool_news_html', "<ul>\n<li><span class=\"important\">" . date('Y-m-d') . "</span> Pool launched</li>\n</ul>")) }}</textarea>
							</p>

							@if ($errors->has('pool_news_html'))
								<p class="help is-danger">
									{{ $errors->first('pool_news_html') }}
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

@section('scripts')
	<script>
		var adminSettingsView = new adminSettingsView();
	</script>
@endsection
