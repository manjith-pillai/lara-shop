@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
<h2>Food Delivery Restaurants in {{$city->city_name}}</h2>
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
    <p>Zapdel is a company that is pioneering the food delivery market with its huge network of riders and promises to deliver all orders irrespective of order amount and distance within the same city so you get to eat from your favorite restaurants at the comfort of your house along with free delivery.  So download our app from the play store and start ordering and enjoy good food at even greater ease.</p>

<p>We all know that food is something one does not compromise with, people go to great lengths to eat a particular dish in their city. With Zapdel you don't have to go to great lengths to get your favorite food but all you have to do is download the app or visit our website. Zapdel brings to you food from your favorite eating joints with no minimum order amount and no constraint on distance. So with Zapdel you let us do the hard work for you and we deliver your favorite food right at your doorstep. online food order in Noida and order from home delivery restaurants in greater Noida  using Zapdel app and enjoy your favorite dishes from around your city without any extra charges. With the emergence of food delivery, eating out is a rare practice after all no restaurant can beat the comfort of your home. Use our Zapdel app to order food online in Noida with no minimum order requirement and no distance constraint.</p> 

<p>We all love Pizzas with Zapdel pizza delivery in Noida becomes not just easier but also cheaper and more comfortable. Online Pizza order in Noida and greater Noida can be done using our awesome android application and website. </p>

<p>Zapdel has a huge collection of restaurants tied up, so you can order food online from the comfort of your home. Through this huge range of restaurants to choose from & an amazing logistics fleet, home delivery of food is made easy with Zapdel. Use the app to order pizza in Noida or Greater Noida, leaving the connection with restaurants entirely on us while home delivery from a huge selection of restaurants is made possible with Zapdel.</p>

<p>Zapdel introduced free home delivery in Noida & still is the food logistics enabler with no minimum distance and no minimum order amount. Use Zapdel for online food home delivery. Zapdel delivers food to your home or office free. Order Pizza online from the variety of restaurants tied up with us. Get food delivered to your doorstep and enjoy free online food delivery. We take pride in being the awesomest delivery service for online food delivery in Noida and Greater Noida & have the best set of delivery boys in house for supporting the Home delivery Restaurants in Noida and Greater Noida to reach consumers in all localities of the city through our free online delivery in Noida and Greater Noida.</p>

<p>Zapdel has many restaurants tied up to enable you order good food online from the variety of restaurant and get food delivered to your doorstep. Our network of restaurant works round to clock to ensure that you get your food delivered in time and enjoy good food at the comfort of your house. Use our application to order food online. Online food Delivery is now made easy in Noida and Greater Noida with Zapdel. Remember to use our application for free home delivery in Noida and Greater Noida.</p>

  </div>
</div>     
</div>

        
</div>
@stop