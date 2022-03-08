@extends("layouts.".config('app.theme').".master")

@section('content')
	<x-filter btn-export-xls="off">
	<form>
	@include('components.form.row', [
		'input'=>[
			['label'=>'Nomor Uji', 'type'=>'text', 'name'=>'no_uji', 'value'=>request()->get('no_uji'), 'col'=>'col-md-8 col-sm-8'],
			['label'=>'Nomor Kendaraan', 'type'=>'text', 'name'=>'no_kendaraan', 'value'=>request()->get('no_kendaraan'), 'col'=>'col-md-4 col-sm-4'],
		]
	])
	</form>
	</x-filter>

	<x-card.with_element>
		@section('header_left', 'Kendaraan Pelanggan')
		<x-table class="table table-striped">
			@section('thead')
				<th style="width:120px">#</th>
				<th>Tgl Registrasi</th>
				<th>No Uji</th>
				<th>No Kendaraan</th>
				<th>Pelanggan</th>
				<th>Keterangan</th>
			@endsection
			
			@if(isset($CustKendaraan) && count($CustKendaraan) > 0)
				@foreach($CustKendaraan as $data)
					<tr>
						<td>{!! printOptions('kendaraan-pelanggan', $data->kendaraan_id) !!}</td>
						<td>{{ $data->created_at }}</td>
						<td>{{ $data->no_uji }}</td>
						<td>{{ $data->no_kendaraan }}</td>
						<td>{{ isset($data->member->nama) ? $data->member->nama : '' }}</td>
                        <td>{{ $data->keterangan }}</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td style="text-align:center" colspan="6">{!! config('message.page_emtpy_record') !!}</td>
				</tr>
			@endif
			
		</x-table>
		
		@include('components.pagination', ['result'=>$CustKendaraan])
		
	</x-card.with_element>
@endsection