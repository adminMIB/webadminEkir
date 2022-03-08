@extends("layouts.".config('app.theme').".master")

@section('content')	
	<x-card.with_element>
		@section('header_left', 'Edit')
		
		<form method="post" action="{{ route('update_akun_user', [$User->user_id]) }}">
		@csrf
		
		@include('components.form.horizontal', [
			'input'=>[
				['label'=>'Nama', 'type'=>'text', 'name'=>'display_name', 'value'=>$User->display_name, 'attributes'=>['required'=>'required']],
				['label'=>'Email', 'type'=>'email', 'name'=>'user_email', 'value'=>$User->user_email, 'attributes'=>['required'=>'required']],
				['label'=>'Telpon', 'type'=>'text', 'name'=>'user_phone', 'value'=>$User->user_phone, 'attributes'=>['required'=>'required']],
				['label'=>'Status', 'type'=>'select', 'name'=>'user_status', 'value'=>$User->user_status, 'options'=>config('flagging.status_global'), 'attributes'=>['required'=>'required']],
				['label'=>'Password', 'type'=>'password', 'name'=>'password', 'value'=>''],
				['label'=>'Re-Password', 'type'=>'password', 'name'=>'repassword', 'value'=>''],
			]
		])
		
		</form>	
	</x-card.with_element>
@endsection
@section('register_script_footer')
	<script>
		// Bootstrap Material DateTimePicker
		$(function() {
			var sHTML ='<small class="valid-feedback"><i>Jika tidak ada perubahan, dikosongkan saja</i></small>';
			$(sHTML).appendTo($("input[name='password']").closest('div'));
			$(".valid-feedback").show();

		});
	</script>
@endsection