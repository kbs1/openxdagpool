@extends('layouts.admin')

@section('title')
	Pool state
@endsection

@section('hero')
	<section class="hero is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Pool state
				</h1>
				<h2 class="subtitle">
					View pool daemon state and version
				</h2>
			</div>
		</div>
	</section>
@endsection

@section('adminContent')
	<nav class="card">
		<header class="card-header">
			<p class="card-header-title">
				Pool state
			</p>
		</header>

		<div class="card-content">
			<p>
				@if (!$state_normal)
					Pool state <strong>(abnormal)</strong>:
				@else
					Pool state:
				@endif
<pre>{{ $state }}</pre>
			</p>
			<p>
				Stats: <br>
<pre>{{ $stats }}</pre>
			</p>
		</div>
	</nav>
@endsection
