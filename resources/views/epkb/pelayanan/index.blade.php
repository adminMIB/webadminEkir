@extends("layouts.".config('app.theme').".master")

@section('content')
	<x-filter btn-export-xls="off">
	<form>
	@include('components.form.row', [
		'input'=>[
			['label'=>'Pelayanan', 'type'=>'text', 'name'=>'pelayanan', 'value'=>request()->get('pelayanan'), 'col'=>'col-md-8 col-sm-8'],
			['label'=>'Status', 'type'=>'select', 'name'=>'status', 'value'=>request()->get('status'), 'options'=>config('flagging.status_pelayanan'), 'col'=>'col-md-4 col-sm-4'],
		]
	])
	</form>
	</x-filter>

	<x-card.with_element>
		@section('header_left', '')
		@if(Permissions::can('create-pelayanan'))
			@section('header_right')
				@include('components.buttons.create_new', ['route'=>route('create_pelayanan'), 'caption'=>'Entri Layanan Baru'])
			@endsection
		@endif

		<x-table class="table table-striped">
			@section('thead')
				<th style="width:120px">#</th>
				<th>Pelayanan</th>
				<th>Status</th>
				<th>Persyaratan</th>
			@endsection
			
			@if(isset($Pelayanan) && count($Pelayanan) > 0)
				@foreach($Pelayanan as $data)
					<tr>
						<td>{!! printOptions('pelayanan', $data->id, ['btn_detail'=>'on']) !!}</td>
						<td>{{ $data->subject }}</td>
						<td>@status($data->status)</td>
                        <td>{!! shortContent($data->content) !!}</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td style="text-align:center" colspan="4">{!! config('message.page_emtpy_record') !!}</td>
				</tr>
			@endif
			
		</x-table>
		
		@include('components.pagination', ['result'=>$Pelayanan])
		
	</x-card.with_element>
@endsection