@extends("layouts.".config('app.theme').".master")

@section('content')	
	<x-card.with_element>
		@section('header_left', 'Edit Akun Member')
		
		<form method="post" action="{{ route('update_member', [$Account->user_id]) }}">
		@csrf
		
		@include('components.form.horizontal', [
			'input'=>[
				['label'=>'Nama', 'type'=>'text', 'name'=>'nama', 'value'=>$Account->nama, 'attributes'=>['readonly'=>'readonly']],
				['label'=>'NIK', 'type'=>'text', 'name'=>'ektp', 'value'=>$Account->ektp, 'attributes'=>['readonly'=>'readonly']],
				['label'=>'Email', 'type'=>'email', 'name'=>'user_mail', 'value'=>$Account->user_mail, 'attributes'=>['required'=>'required']],
				['label'=>'Telpon', 'type'=>'text', 'name'=>'no_handphone', 'value'=>$Account->no_handphone, 'attributes'=>['readonly'=>'readonly']],
				['label'=>'Alamat', 'type'=>'text', 'name'=>'alamat', 'value'=>$Account->alamat, 'attributes'=>['readonly'=>'readonly']],
				['label'=>'Status', 'type'=>'select', 'name'=>'status', 'value'=>$Account->status, 'options'=>config('flagging.status_global'), 'attributes'=>['required'=>'required']],
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