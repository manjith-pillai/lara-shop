@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
<h2>Home Delivery in {{$city->city_name}}</h2>
<hr>
<div class="row">
 <div class="col-md-6 form-search" style="margin-bottom: 25px;">
               
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Enter Dish Name or Cuisines">
      <span class="searchdiv">
        <button class="btn btn-default searchbtn" type="button">Search</button>
      </span>
    </div>
  
 	</div>
 </div>
 <div class="row">
   <div class="col-md-12">
   	<h4>Restaurant Available in {{$city->city_name}}:</h4>
   <ul style="list-style: none;
    -moz-column-count: 4;
    -moz-column-gap: 20px;
    -webkit-column-count: 4;
    -webkit-column-gap: 20px;
    column-gap: 20px;padding-left:0">
    @foreach($result as $city)
   	<li><a href="{{url($city->name.'/'.$city->url_name)}}">{{$city->name}}</a></li>
   	@endforeach
   </ul>
    <a href="#" class="btn btn-default">Order Now</a>
   </div>       
</div>
<div class="row" style="margin-bottom: 30px;margin-top: 30px;">
	<div class="col-md-12">
		<p>Zapdel is a company that is making its way into the online food delivery market real fast. It promises to deliver all kinds of orders without any constraints on the amount of the order and the distance of the restaurant within the same city, in an attempt to making the whole process of you eating your favorite food from your favorite places all the more comfortable and that too without any extra charges since Zapdel provides free home delivery. And all you are required to do in order to enjoy all these amazing perks is to download our app or visit our website and start ordering.</p>
<p>Zapdel with its huge collection of restaurant along with our extensive network of riders have made home delivery of food in Surat an easy and happy experiences.</p> 
<p>If you neither want to make any extra efforts nor compromise on your food, Zapdel is just the app for you. Now you can Online food order in Surat by using Zapdel with no restrictions on the minimum amount of the order or the distance and that too without having to spend an extra penny from your pocket, and all this happens while you sit comfortably at your place.  Use our Zapdel app or visit our website to order home delivery food in Surat.</p>
<p>With the long working hours and hectic schedules who has the time to visit a restaurant. Now while you sit at home comfortably let us along with our tied up restaurants and the best in house delivery boys do all the work for you in making your favorite food reach you from restaurants all around the city of Chandigarh. A quick and reliable service is ensured in our part. Online food order in Surat with free delivery without any constraints on the amount and the distance by using the Zapdel app or visiting our website.</p>   
	</div>
</div>
        
</div>
@stop