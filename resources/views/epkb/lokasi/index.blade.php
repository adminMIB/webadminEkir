@extends("layouts.".config('app.theme').".master")

@section('content')
	<x-card.with_element>
		@section('header_left', '')
		@if(Permissions::can('create-lokasi'))
			@section('header_right')
				@include('components.buttons.create_new', ['route'=>route('create_lokasi'), 'caption'=>'Lokasi Baru'])
			@endsection
		@endif

		<x-table class="table table-striped">
			@section('thead')
				<th style="width:100px">#</th>
				<th>Lokasi</th>
				<th>Status</th>
				<th>Map URL</th>
			@endsection
			
			@if(isset($Lokasi) && count($Lokasi) > 0)
				@foreach($Lokasi as $data)
					<tr>
						<td>{!! printOptions('lokasi', $data->id) !!}</td>
						<td>{{ $data->subject }}</td>
						<td>@status($data->status)</td>
                        <td><a href="{{ $data->content }}" target="_blank">{{ $data->content }}</a></td>
					</tr>
				@endforeach
			@else
				<tr>
					<td style="text-align:center" colspan="4">{!! config('message.page_emtpy_record') !!}</td>
				</tr>
			@endif
			
		</x-table>
		
		@include('components.pagination', ['result'=>$Lokasi])
		
	</x-card.with_element>
@endsection