@extends("layouts.".config('app.theme').".master")

@section('content')
	<x-filter btn-export-xls="off">
	<form>
	{!!
	formRow(
		[
			[
				['Nama' => ['type'=>'text', 'name'=>'nama', 'value'=>request()->get('nama'), 'col'=>'col-md-4 col-sm-6']],
				['Email' => ['type'=>'text', 'name'=>'email', 'value'=>request()->get('email'), 'col'=>'col-md-4 col-sm-6']],
				['Status' => ['type'=>'select', 'name'=>'status', 'value'=>request()->get('status'), 'options'=>config('flagging.status_global'), 'col'=>'col-md-4 col-sm-6']],
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
		@section('header_left', $sub_title)

		<x-table class="table table-striped">
			@section('thead')
				<th style="width:120px">#</th>
				<th>Tgl&nbsp;Registrasi</th>
				<th>Email</th>
				<th>Nama</th>
				<th>Status</th>
			@endsection
			
			@if(isset($Account) && count($Account) > 0)
				@foreach($Account as $data)
					<tr>
						<td>{!! printOptions('member', $data->user_id, ['btn_detail'=>'on']) !!}</td>
						<td>{{ $data->created_at }}</td>
						<td>{{ $data->user_mail }}</td>
						<td>{{ $data->nama }}</td>
						<td>@status($data->status)</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td style="text-align:center" colspan="4">{!! config('message.page_emtpy_record') !!}</td>
				</tr>
			@endif
			
		</x-table>
		
		@include('components.pagination', ['result'=>$Account])
		
	</x-card.with_element>
@endsection