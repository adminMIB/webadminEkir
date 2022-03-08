<div class="card mb-4">
	<h6 class="card-header with-elements">
		<div class="card-header-title">@yield('header_left')</div>
		<div class="card-header-elements ml-auto">
		  @yield('header_right')
		</div>
	</h6>
	<div class="card-body">
		{{ $slot }}
	</div>
</div>