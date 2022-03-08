<div {{ $attributes->merge(['class' => 'card mb-4']) }}>
	@if($title)
    <div class="card-header">
	<h6 style="margin-bottom:0">{!! $title !!}</h6>
    </div>
	@endif
	
	@yield('card_header')
	
    <div class="card-body">
	 {{ $slot }}
    </div>
	@yield('card_footer')
</div>