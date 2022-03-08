<div class="form-row">
	@foreach($input as $inp)
	<div class="form-group {!! $inp['col'] !!}">
		<label class="form-label text-muted"><small>{{ $inp['label'] }}</small></label>
		@if($inp['type'] == "select")
			@include('components.form.inputs.select', $inp)
		@else
			@include('components.form.inputs.text', $inp)
		@endif
	</div>
	@endforeach
</div>