@extends("layouts.".config('app.theme').".master")
@section('content')
<x-card.with_element>
	@section('header_left', $Account->nama)

	<div>
		<small class="text-muted">Nama :</small>
		<h6 class="ml-2 mb-3">{{ $Account->nama }}</h6>
	</div>

	<div>
		<small class="text-muted">Tgl Registrasi :</small>
		<h6 class="ml-2 mb-3">{{ $Account->created_at }}</h6>
	</div>

	<div>
		<small class="text-muted">Email :</small>
		<h6 class="ml-2 mb-3">{{ $Account->user_mail }}</h6>
	</div>

	<div>
		<small class="text-muted">Phone :</small>
		<h6 class="ml-2 mb-3">@phoneFormat($Account->no_handphone)</h6>
	</div>

	<div>
		<small class="text-muted">NIK :</small>
		<h6 class="ml-2 mb-3">{{ $Account->ektp }}</h6>
	</div>

	<div>
		<small class="text-muted">Alamat :</small>
		<h6 class="ml-2 mb-3">{{ $Account->alamat }}</h6>
	</div>
	
</x-card.with_element>
<x-card>
{!! pagePrintOptions('member', $Account->user_id, route('member')) !!}
</x-cart>
@endsection