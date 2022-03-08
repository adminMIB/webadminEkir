@extends("layouts.".config('app.theme').".master")

@section('content')
	<x-filter btn-export-xls="off">
	<form>
	{!!
	formRow(
		[
			[
				['Judul' => ['type'=>'text', 'name'=>'subject', 'value'=>request()->get('subject'), 'col'=>'col-md-9 col-sm-6']],
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
		@section('header_left', 'Informasi/Berita')
		@if(Permissions::can('create-informasi'))
			@section('header_right')
				@include('components.buttons.create_new', ['route'=>route('create_informasi'), 'caption'=>'Informasi/Berita Baru'])
			@endsection
		@endif

		<x-table class="table table-striped">
			@section('thead')
				<th style="width:120px">#</th>
				<th>Judul</th>
				<th>Status</th>
				<th>Isi/Konten</th>
			@endsection
			
			@if(isset($Informasi) && count($Informasi)>0 )
				@foreach($Informasi as $data)
					<tr>
						<td>{!! printOptions('informasi', $data->id, ['btn_detail'=>'on']) !!}</td>
						<td>{{ $data->subject }}</td>
						<td>@status($data->status)</td>
                        <td>{!! shortContent($data->content, 80) !!}</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td style="text-align:center" colspan="7">{!! config('message.page_emtpy_record') !!}</td>
				</tr>
			@endif
			
		</x-table>
		
		@include('components.pagination', ['result'=>$Informasi])
		
	</x-card.with_element>
@endsection