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
    <p>Zapdel is a company that is making its way into the online food delivery market real fast. It promises to deliver all kinds of orders without any constraints on the amount of the order and the distance of the restaurant within the same city, in an attempt to making the whole process of you eating your favorite food from your favorite places all the more comfortable and that too without any extra charges since zapdel provides free home delivery. And all you are required to do in order to enjoy all these amazing perks is to download our app or visit our website and start ordering.</p>
<p>If you neither want to make any extra efforts nor compromise on your food, zapdel is just the app for you. Now you can Online food order in Indore by using Zapdel with no restrictions on the minimum amount of the order or the distance and that too without having to spend an extra penny from your pocket, and all this happens while you sit comfortably at your place.  Use our zapdel app or visit our website to order online food delivery in Indore.</p>
<p>Zapdel has tied up with a huge number of restaurants all around the city of Indore and it thus provides its customers with a huge range of choices to choose from. While you sit at home comfortably and order your favorite food with just a click, we walk that extra mile for you and deliver your food at your doorstep that that too without any extra charges and constraints of amount and distance. Use our app in Indore to order food online with no minimum order requirement and no distance constraint.  
With times changing really fast, going out to a restaurant has been largely replaced by ordering food online. And zapdel helps you to keep with these changing times by making the whole process a lot easier for you. Zapdel has made Online food delivery in Indore possible by ensuring to deliver food from anywhere around the city to your doorstep without any constraints of amount and distance. Use Zapdel for online food order in Indore to your office or home with at most ease. We take pride in being the most amazing online delivery service with a huge range of restaurants to offer and an amazing fleet of logistics in Indore. Fast and reliable service is ensured on our part.</p> 
<p>Zapdel is pioneering in online food delivery in Indore with its restaurants and best in house delivery boys who work the extra mile for you and we ensure to put our best foot forward to reach people in all the localities of Indore. Remember to use the zapdel app or visit our website for the best online food delivery service that you can have. Happy ordering with us!</p>

  </div>
</div>  
</div>
        
</div>
@stop