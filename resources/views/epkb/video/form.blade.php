@extends("layouts.".config('app.theme').".master")

@section('content')	
	<x-card.with_element>
		@section('header_left', $sub_title)
		
		<form method="post" action="{{ isset($Video->id) ? route('update_video', ['id'=>$Video->id]) : route('store_video') }}">
		@csrf
		
		@include('components.form.horizontal', [
			'input'=>[
				['label'=>'Judul Video', 'type'=>'text', 'name'=>'subject', 'value'=>isset($Video->id)?$Video->subject:'', 'attributes'=>['required'=>'required']],
				['label'=>'Link Video', 'type'=>'text', 'name'=>'video', 'value'=>isset($Video->id)?$Video->content:'', 'attributes'=>['required'=>'required']],
				['label'=>'Status', 'type'=>'select', 'name'=>'status', 'value'=>isset($Video->id)?$Video->status:'1', 'options'=>config('flagging.status_global'), 'attributes'=>['required'=>'required']],
			]
		])
		
		</form>	
	</x-card.with_element>
	
@endsection