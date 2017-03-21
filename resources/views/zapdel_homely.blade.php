@extends('layoutn.main')
@section('content')

@include('sign-in-modal')

<div class="container" style="padding-top: 70px;">
	<h1>Zappmeal</h1>
	<hr>
<div class="row" style="margin-bottom:40px">
	<div class="col-md-4 text-center"> 
		<img src="{{URL::asset('/img/assets/mo.jpg')}}" style="width:300px" alt="">
	</div>
	<div class="col-md-8" style="padding-top:60px">
		<h3>Nothing is better than a tasty, healthy food with a homely touch.</h3>
		<p>Based on the consumer surveys done by our research team across different cities, it was found out that people prefer home-made food. But amidst busy life due to long professional hours , food is lost somewhere in between. Our customers who regularly order from us feels that they can’t rely on restaurants food for daily basis and strongly fell the need of home-cooked food in their lives.</p>
		<p>That’s where we come in with the product, <strong>Zappmeal.</strong></p>
		<a class="btn btn-default" href="{{url('Noida/search/restaurant/28@5815814/77@31835679999999')}}">Order Now</a>
	</div>
</div>
	
</div>


@stop