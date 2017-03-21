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
    <p>Zapdel, a company that is making its place into the food delivery market real fast promises its customers to deliver all kinds of orders without any constraints on the amount of the order and the distance of the restaurant within the same city, in an attempt to making the whole process of you eating your favorite food from your favorite places all the more comfortable and that too without any extra charges since zapdel provides free home delivery. All you are required to do is download the zapdel app or visit our website and start ordering your favorite food.</p>
<p>Instead of traveling all those dusty miles just to grab a bite of your favorite food from your favorite restaurant, now you can sit comfortably at home and get your favorite food delivered to your doorstep from all around the city of Chandigarh. Online food order in Chandigarh is now easy and fast with the help of Zapdel. Zapdel has a huge network of restaurants tied to it all around the city of Chandigarh so that ordering your favorite food online from your favorite places is just a click away without spending an extra penny and without any constraints on the amount of the order or the distance. Use our app or visit the zapdel website to order your get your favorite food delivered to your home or office.</p> 
<p>With the long working hours and hectic schedules who has the time to visit a restaurant. Now while you sit at home comfortably let us along with our tied up restaurants and the best in house delivery boys do all the work for you in making your favorite food reach you from restaurants all around the city of Chandigarh. A quick and reliable service is ensured in our part. Online food order in Chandigarh with free delivery without any constraints on the amount and the distance by using the zapdel app or visiting our website.</p>     


  </div>
</div>      
</div>
        
</div>
@stop