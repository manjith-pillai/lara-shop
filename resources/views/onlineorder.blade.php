@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
<h2>Online Order in {{$city->city_name}}</h2>
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
		<p>Zapdel is pioneering its way into the online food delivery market. It provides its customers with food delivery of all kinds irrespective of the amount of the order and the distance of the restaurant within the same city. It is an effort to make your experience of eating your favorite food from your all time favorite food joints much more comfortable and easy. And this is done without spending any extra money as zapdel provides with free home delivery and online food order in Lucknow. So without any further wait, download our zapdel app or visit our website and start ordering your favorite food right away.</p> 
<p>Now instead of travelling all those extra miles just to grab a bite of your favorite food, all you have to do is sit comfortably at your place and order food with at most ease from all around the city of Lucknow. Zapdel has a huge network of restaurants tied to it all around the city of Lucknow so that online food order in Lucknow from your favorite places is just a click away without spending an extra penny and without any constraints on the amount of the order or the distance. Use our app in Lucknow to order food online with no minimum order requirement and no distance constraint.</p> 
<p>Nothing beats the comfort and peace of your home and having your favorite food from your favorite restaurant while staying at home adds a cherry on top. Zapdel has introduced in Lucknow free online food delivery without the restrictions on the amount of the order and the distance. We along with our restaurants and the best in house delivery boys work that extra mile so that you can have your all time favorite food from all around the city of Lucknow delivered to your doorstep that too without any extra charges. So don’t forget to use our app or visit our website for the best food delivery service.</p>
	</div>
</div>
        
</div>
@stop