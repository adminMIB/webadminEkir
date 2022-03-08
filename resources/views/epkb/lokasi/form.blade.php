@extends("layouts.".config('app.theme').".master")

@section('content')	
	<x-card.with_element>
		@section('header_left', $sub_title)
		
		<form method="post" action="{{ isset($Lokasi->id) ? route('update_lokasi', ['id'=>$Lokasi->id]) : route('store_lokasi') }}">
		@csrf
		@include('components.form.horizontal', [
			'input'=>[
				['label'=>'Lokasi', 'type'=>'text', 'name'=>'subject', 'value'=>isset($Lokasi->id)?$Lokasi->subject:'', 'attributes'=>['required'=>'required']],
				['label'=>'Status', 'type'=>'select', 'name'=>'status', 'value'=>isset($Lokasi->status)?$Lokasi->status:'', 'options'=>config('flagging.status_global'), 'attributes'=>['required'=>'required']],
				['label'=>'Map URL', 'type'=>'text', 'name'=>'content', 'value'=>isset($Lokasi->content)?$Lokasi->content:'', 'attributes'=>['required'=>'required']],
			]
		])
		</form>	
	</x-card.with_element>
	
@endsection