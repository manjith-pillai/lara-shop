@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
<h2>Food Delivery in {{$city->city_name}}</h2>
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
    <p>Zapdel, a company that is fast pioneering its way into the food delivery market. It ensures its customers the delivery of all kinds of orders irrespective of the amount of the order and the distance of the restaurant within the same city, in an attempt to make your experience of eating your favorite food from your favorite places all the more comfortable that too without spending anything extra on home delivery because Zapdel provides free delivery. Now to enjoy all these perks all you have to do is download our app or visit the zapdel website and start placing your orders. </p>
<p>Zapdel has introduced online food delivery in Agra without the restrictions on the amount of the order and the distance. Zapdel has a large network of restaurants tied to it and provides you with a huge range of choices of food joints and cuisines to choose from all around the city of Agra. . We take pride in being the most amazing online food delivery service in Agra with a huge range of restaurants to offer and an amazing fleet of logistics in Agra. Fast and reliable service is ensured on our part. Use our app or visit our website to order food online in Agra.</p>
<p>Online food delivery in Agra is shadowing visiting restaurants more and more, zapdel promises to make this whole process a lot easier and comfortable for you. While you sit at home and comfortably order your all time relished food from your favorite restaurant, we along our tied up restaurants and best in house delivery boys work hard and ensure that your food is delivered to your doorstep just in time. So now with zapdel your favorite food from all around the city of Agra is just a click away. Don’t forget to use our app or visit our website and start placing your orders right away. Happy ordering!</p>

  </div>
</div>  
</div>
        
</div>
@stop