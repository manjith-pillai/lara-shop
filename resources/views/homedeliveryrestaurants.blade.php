@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
<h2>Home Delivery Restaurant in {{$city->city_name}}</h2>
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
    <p>Zapdel is a company that is spearheading its way into the online food delivery market in Ahmedabad. It ensures its customers the delivery of all kinds of orders irrespective of the amount of the order and the distance of the restaurant within the same city, in an attempt to make your experience of eating your favorite food from your favorite places all the more comfortable that too without spending anything extra on home delivery because Zapdel provides free delivery. In order to avail all these benefits and online food order in Ahmedabad, all you need to do is download our app from the play store and start ordering.</p>
<p>Now, instead of traveling all those dusty miles just to grab a bite of that delicious food from your favorite food joint all you have to do is sit at home comfortably and order your food just by using our app or visiting our website. Zapdel brings the experience of having your all time relished food from the restaurants of your choice to your doorstep without any extra charges, that too without any restrictions on the minimum amount of order or the maximum distance of the restaurant. Use our zapdel app or visit our website to order food online in Ahmedabad.</p>
<p>Zapdel has a huge network of home delivery restaurants in Ahmedabad so that ordering your favorite food online from your favorite places is just a click away without spending an extra penny. While we do all the work in providing you with a huge list of restaurants serving all kinds of cuisines to chose from, all you have to do is sit at home comfortably and order online food delivery in Ahmedabad using our app. Use our app for online food order in Ahmedabad with no minimum order requirement and no distance constraint.</p>
<p>In today’s fast life and tiring working days, nothing is better than enjoying your favorite food sitting at home comfortably without having to make any extra efforts or spend any extra money.  Zapdel has made online food delivery in Ahmedabad possible by ensuring to deliver food from anywhere around the city to your doorstep without any constraints of amount and distance. Use Zapdel for online food order in Ahmedabad with at most ease. We take pride in being the most amazing online delivery service with a huge range of restaurants to offer and an amazing fleet of logistics in Ahmadabad. Fast and reliable service is ensured on our part.</p> 
<p>Zapdel has introduced Ahmadabad to free online food delivery without the restrictions on the amount of the order and the distance. We along with our tied up restaurants and best in house delivery boys work the extra mile for you and put our best foot forward to reach people in all the localities of Ahmadabad. Online food delivery is now just a click away in Ahmadabad with zapdel. Don’t forget to use the app or visit our website.</p>

  </div>
</div>    
</div>
        
</div>
@stop