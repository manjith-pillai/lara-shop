@extends('layoutn.main')
@section('content')
@include('sign-in-modal')

<div class="container" style="padding-top: 70px;">
<div class="col-md-12 text-center" style="margin-top: 30px;margin-bottom: 50px">
	<img src="{{asset('img/assets/404error.png')}}" style="height: 200px;" alt="">
	<h1>404 Error</h1>
	<h3>Page Not Found</h3>
	<h5>Sorry, this page isn't available. You could return to the homepage <a href="{{url('/')}}">Zapdel.com</a></h5>
</div>

</div>

@stop