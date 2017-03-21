@extends('layoutn.main')
@section('content')
@include('sign-in-modal')

<div class="container" style="padding-top: 70px;">
<div class="col-md-12 text-center" style="margin-top: 30px;margin-bottom: 50px">
	<img src="{{asset('img/assets/500error.png')}}" style="height: 200px;" alt="">
	<h1>500 Error</h1>
	<h3>Oops!! Something Went Wrong</h3>
	<h5>Why not refreshing your page? Or try after sometime</h5>
</div>

</div>

@stop