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
			<p>Pool version: {{ $version }}</p>
			<p>
				Pool state: {{ $state }}
				@if (!$state_normal)
					<strong>(abnormal)</strong>
				@endif
			</p>
		</div>
	</nav>
@endsection
