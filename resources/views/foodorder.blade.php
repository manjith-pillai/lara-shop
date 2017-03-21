@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
<h2>Food Order in {{$city->city_name}}</h2>
<hr>
<div class="row">
 <div class="col-md-6 form-search" style="margin-bottom: 25px;">
    <form action="https://www.zapdel.com/{{$city->city_name}}/search/restaurant/28@5700/77@3200">         
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Enter Dish Name or Cuisines">
      <span class="searchdiv">
        <button class="btn btn-default searchbtn" type="submit">Search</button>
      </span>
    </div>
  </form>  
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
      <a href="https://www.zapdel.com/{{$city->city_name}}/search/restaurant/28@5700/77@3200" class="btn btn-default">Order Now</a>
   </div> 
   </div> 
   
<div class="row" style="margin-bottom: 30px;margin-top: 30px;">
  <div class="col-md-12">
    What brings a smile on a famished man's face? What it is the ultimate fantasy of a foodie? What is the basic necessity of every human being? The only answer is Food. Being the third largest city of Gujarat, Vadodara has one of the finest restaurants of the city and with sudden boom in the delivery restaurants food delivery in Vadodara has seen a tremendous growth in last few years. With the increasing number of online users in the city, online food order in Vadodara has become a common practice. But with major logistics problem, food delivery in Vadodara was always a hurdle for restaurants. To close the gap between Restaurants and customers Zapdel was started in Vadodara on November 2013 with the prime motto to help restaurants and customers with food delivery in Vadodara. With time the number increased in online food orders in Vadodara and with increasing numbers increased the happy faces of customers. The most luring part of Zapdel is that we don't have a maximum area limit so food delivery in Vadodara became more common through Zapdel. With growing internet usage Online Food order in Vadodara is giving tough competition to restaurants and even fine-dining restaurants have associated themselves with Zapdel for food delivery in Vadodara which has increased their revenue. Zapdel is now operational in 11 cities in India and taking food delivery in Vadodara and other cities to the next level.

  </div>
</div>      
</div>
        
</div>
@stop