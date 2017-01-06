@extends('base')

@section('title', 'Welcome')

@section('content')
	<div class="container">
		<songs-list logged-in="{{ Auth::check() }}"></songs-list>
	</div>
@stop