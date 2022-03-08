@extends("layouts.".config('app.theme').".master")

@section('content')	
	<x-card.with_element>
		@section('header_left', $sub_title)
		
		<form method="post" action="{{ isset($CustKendaraan->kendaraan_id) ? route('update_kendaraan_pelanggan', ['id'=>$CustKendaraan->kendaraan_id]) : route('store_kendaraan_pelanggan') }}">
		@csrf
		
		@include('components.form.horizontal', [
			'input'=>[
				['label'=>'Nomor Uji', 'type'=>'text', 'name'=>'no_uji', 'value'=>isset($CustKendaraan->no_uji)?$CustKendaraan->no_uji:'', 'attributes'=>['required'=>'required']],
				['label'=>'Nomor Kendaraan', 'type'=>'text', 'name'=>'no_kendaraan', 'value'=>isset($CustKendaraan->no_kendaraan)?$CustKendaraan->no_kendaraan:''],
				['label'=>'Keterangan', 'type'=>'text', 'name'=>'keterangan', 'value'=>isset($CustKendaraan->keterangan)?$CustKendaraan->keterangan:''],
			]
		])
		
		</form>	
	</x-card.with_element>
	
@endsection