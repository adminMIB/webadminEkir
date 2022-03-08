@extends("layouts.".config('app.theme').".master")
@section('content')
<x-card.with_element>
	@section('header_left', $Pelayanan->subject)
	
	{!! $Pelayanan->content !!}
</x-card.with_element>
<x-card>
{!! pagePrintOptions('pelayanan', $Pelayanan->id, route('pelayanan')) !!}
</x-cart>
@endsection