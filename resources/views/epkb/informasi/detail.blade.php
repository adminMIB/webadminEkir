@extends("layouts.".config('app.theme').".master")
@section('content')
<x-card.with_element>
	@section('header_left', $Informasi->subject)
	<img src="{{ $Informasi->img_url }}" class="mb-2 rounded img-thumbnail" />
	{!! $Informasi->content !!}
</x-card.with_element>
<x-card>
{!! pagePrintOptions('informasi', $Informasi->id, route('informasi')) !!}
</x-cart>
@endsection