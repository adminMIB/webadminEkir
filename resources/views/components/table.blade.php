<div class="table-responsive">
	<table {{ $attributes->merge(['class' => 'table']) }}>
	  @if (trim($__env->yieldContent('thead')))
	  <thead>
		<tr>
		  @yield('thead')
		</tr>
	  </thead>
	  @endif
	  <tbody>
	  {{ $slot }}
	  </tbody>
	</table>
</div>