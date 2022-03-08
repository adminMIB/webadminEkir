@extends("layouts.".config('app.theme').".master")

@section('content')
	<x-filter btn-export-xls="off">
	<form>
	{!!
	formRow(
		[
			[
				['Judul Video' => ['type'=>'text', 'name'=>'subject', 'value'=>request()->get('subject'), 'col'=>'col-md-6 col-sm-6']],
				['Status' => ['type'=>'select', 'name'=>'status', 'value'=>request()->get('status'), 'options'=>config('flagging.status_global'), 'col'=>'col-md-3 col-sm-6']],
			],
			['button' => [
				['type'=>'submit', 'class'=>'btn-primary', 'icon'=>'ion ion-md-sync', 'caption'=>'Filter']
			]]
		]
	)
	!!}
	</form>
	</x-filter>

	<x-card.with_element>
		@section('header_left', 'List Video')
		@if(Permissions::can('create-video'))
			@section('header_right')
				@include('components.buttons.create_new', ['route'=>route('create_video'), 'caption'=>'Entri Video'])
			@endsection
		@endif

		<x-table class="table table-striped">			
			@if(isset($Video) && $Video)
				@foreach($Video as $data)
					<?php
						$arr =explode("v=", $data->content);
						$src = end($arr);
					?>
					<tr>
						<td style="width:80px">{!! printOptions('video', $data->id) !!}</td>
						<td align="left">
						<div style="">
						<embed
							src="https://www.youtube.com/embed/{{ $src }}?autohide=1&autoplay=1&controls=0&disablekb=1&rel=0&modestbranding=1&showinfo=0"
							wmode="transparent"
							type="video/mp4"
							width="100%" height="100%"
							allow="autoplay; encrypted-media; picture-in-picture"
							allowfullscreen
							title="{{ $data->subject }}"
						>
						</div>
							<br>{{ $data->subject }} @status($data->status)
						</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td style="text-align:center" colspan="2">{!! config('message.page_emtpy_record') !!}</td>
				</tr>
			@endif
			
		</x-table>
		
		@include('components.pagination', ['result'=>$Video])
		
	</x-card.with_element>
@endsection