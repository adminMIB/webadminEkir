@extends("layouts.".config('app.theme').".master")

@section('title', config('app.name').' - Access forbidden')
@section('page_title', 'Access Forbidden')

@section('content')
	<x-card title="&nbsp;">
		<p class="alert alert-danger">Directory access is forbidden!</p>
	</x-card>
@endsection