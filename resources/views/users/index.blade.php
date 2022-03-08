@extends("layouts.".config('app.theme').".master")

@section('content')
	<x-filter btn-export-xls="off">
	<form>
	{!!
	formRow(
		[
			[
				['Nama' => ['type'=>'text', 'name'=>'display_name', 'value'=>request()->get('display_name'), 'col'=>'col-md-4 col-sm-6']],
				['Email' => ['type'=>'text', 'name'=>'user_email', 'value'=>request()->get('user_email'), 'col'=>'col-md-4 col-sm-6']],
				['Status' => ['type'=>'select', 'name'=>'status', 'value'=>request()->get('status'), 'options'=>config('flagging.status_persyaratan'), 'col'=>'col-md-4 col-sm-6']],
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
		@section('header_left', 'List Adm/User Web Dashboard Terdaftar')
		@if(Permissions::can('create-akun-user'))
			@section('header_right')
				@include('components.buttons.create_new', ['route'=>route('create_akun_user'), 'caption'=>'Adm/User Baru'])
			@endsection
		@endif

		<x-table class="table table-striped">
			@section('thead')
				<th style="width:120px">#</th>
				<th>Created At</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Nama</th>
				<th>Status</th>
			@endsection
			
			@if(isset($User) && count($User) > 0)
				@foreach($User as $data)
					<tr>
						<td>{!! printOptions('akun-user', $data->user_id) !!}</td>
						<td>{{ $data->user_created_at }}</td>
						<td>@phoneFormat($data->user_phone)</td>
						<td>{{ $data->user_email }}</td>
						<td>{{ $data->display_name }}</td>
						<td>@status($data->user_status)</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td style="text-align:center" colspan="6">{!! config('message.page_emtpy_record') !!}</td>
				</tr>
			@endif
			
		</x-table>
		
		@include('components.pagination', ['result'=>$User])
		
	</x-card.with_element>
@endsection