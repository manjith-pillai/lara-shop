@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
<h2>Pizza Order in {{$city->city_name}}</h2>
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
    <p>Zapdel is a company that is spearheading its way into the online food delivery market. It promises its customers free and quick online food delivery without any constraints on the amount of the order and the distance of the restaurant within the same city. It ensures to make your experience of eating your favorite food from your favorite places all the more comfortable that too without spending anything extra on home delivery because Zapdel provides free delivery. All you have to do is download our app or visit our website and start ordering.</p> 
<p>Now instead of compromising on your pizza and also without having to make any extra efforts you can get your favorite food from your favorite eating joints all around the city of Ghaziabad delivered to your doorstep that too without spending an extra penny from your pocket. Online pizza order in Ghaziabad is made easy as Zapdel has tied up with a huge number of restaurants all around the city of Ghaziabad and it thus provides its customers with a huge range of choices to choose to order from without any restrictions on the amount of the order and the distance of the restaurant. Use our zapdel app or visit our website to order your favorite food.</p> 
<p>In this fast moving world no one has time to visit a restaurant any more. With more and more people shifting to ordering food online, zapdel makes it all the more easy and comfortable. We along with our tied up restaurants and best in house delivery boys ensure you that we will do our best to reach people in all the localities of Ghaziabad. Now remember to order food online for free in Ghaziabad without any constraints on the amount and the distance by using the zapdel app or visiting our website.</p>
  </div>
</div>  
</div>
        
</div>
@stop