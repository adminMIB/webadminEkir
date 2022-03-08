@extends("layouts.".config('app.theme').".master")

@section('content')	
	<x-card.with_element>
		@section('header_left', $sub_title)
		
		<form method="post" action="{{ route('store_akun_user') }}">
		@csrf
		
		@include('components.form.horizontal', [
			'input'=>[
				['label'=>'Nama', 'type'=>'text', 'name'=>'display_name', 'value'=>'', 'attributes'=>['required'=>'required']],
				['label'=>'Email', 'type'=>'email', 'name'=>'user_email', 'value'=>'', 'attributes'=>['required'=>'required']],
				['label'=>'Telpon', 'type'=>'text', 'name'=>'user_phone', 'value'=>'', 'attributes'=>['required'=>'required']],
				['label'=>'Status', 'type'=>'select', 'name'=>'user_status', 'value'=>'1', 'options'=>config('flagging.status_global'), 'attributes'=>['required'=>'required']],
				['label'=>'Password', 'type'=>'password', 'name'=>'password', 'value'=>'', 'attributes'=>['required'=>'required']],
				['label'=>'Re-Password', 'type'=>'password', 'name'=>'repassword', 'value'=>'', 'attributes'=>['required'=>'required']],
			]
		])
		
		</form>	
	</x-card.with_element>
	
@endsection