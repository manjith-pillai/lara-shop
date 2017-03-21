@extends('admin.dashboard.layouts.home')
@section('admin_content')
<div class="container-fluid" style="margin-bottom: 30px">
<h1>Place Order</h1>
<hr>
<div class="row">
	<div class="col-md-4">
		<div class="form-group form-gp-bg @if ($errors->has('city_id')) has-error @endif">
  <label for="city">City:</label>
  <select class="form-control" id="city" name="city_id">
   <option value="" disabled hidden selected>Select City</option>
  @foreach ($cities as $r_city)
    <option value="{{$r_city->city_url_name}}" {{($r_city->city_url_name == $city_url) ? 'selected' : '' }}>{{$r_city->city_name}}</option>
    @endforeach
  </select>
   @if ($errors->has('city_id')) <p class="help-block">{{ $errors->first('city_id') }}</p> @endif
</div>
	</div>
	<div class="col-md-4">
		<div class="form-group form-gp-bg">
			  <label for="restaurant">Restaurant:</label>
			  <select class="form-control rest-list" id="restaurant" name="rest">
			  <option value="" disabled hidden selected>Restaurants</option>
        @foreach($restuarant_list as $list)
			    <option value="{{$list->url_name}}" {{($list->url_name == $rest_url_name) ? 'selected' : '' }}>{{$list->name}}</option>
        @endforeach
			  </select>
		</div>
	</div>
</div>
@if(!empty($restaurantDetails))
<div class="row">

	<div class="col-md-8">
    <div class="r-list">
       <h3>{{$restaurantDetails['details'][0]->name}}</h3> 
    </div>
    <div class="restaurant-menu" style="height: 500px;overflow-y: scroll;">
              <div class="rest-menu-head">
              @if($restaurantDetails['details'][0]->is_homely == 1)
                <h3>Chef's Menu</h3>
                @else
                <h3>Menu</h3>
              @endif
              </div>          
    @for($i=0,$flag=0;$i<sizeof($restaurantDetails['dishes']);$flag=1)  
    <div class="menu-page-cuisine">
    @if($restaurantDetails['details'][0]->is_homely == 1)
      <h2 style="margin-bottom:5px">{{$restaurantDetails['dishes'][$i]->cuisine}}</h6>
      <h6 style="margin-top:0"><i>{{$restaurantDetails['dishes'][$i]->cuisine_description}}</i></h5>
     @else
      <h2>{{$restaurantDetails['dishes'][$i]->cuisine}}</h2>
    @endif  
    @if($i!=0)
    @for(; $i<sizeof($restaurantDetails['dishes']) && ($restaurantDetails['dishes'][$i]->cuisine == $restaurantDetails['dishes'][$i-1]->cuisine || $flag==1);$i++,$flag=0)
    <div class="cuisine-list-block">
      <div class="col-xs-6 menu-cuisine-list menu-item">
        @if($restaurantDetails['dishes'][$i]->veg_flag == 1)
          <img src="{{URL::asset('img/assets/Veg.png')}}" style="width:16px;margin-bottom: 4px;margin-right: 5px;">
        @else
          <img src="{{URL::asset('img/assets/NonVeg.png')}}" style="width:16px;margin-bottom: 4px;margin-right: 5px;">
        @endif
        <span>{{$restaurantDetails['dishes'][$i]->dish_name}}</span>
        
      </div>
      <div class="col-xs-2 menu-item">
        Rs. {{$restaurantDetails['dishes'][$i]->price}}
      </div>

      <div class="col-xs-4 menu-item text-right">
        <span class="glyphicon glyphicon-minus sub" onclick="Restaurant.controllers.subtract('rest-{{$restaurantDetails['dishes'][$i]->id}}')"></span>
        <span id="rest-{{$restaurantDetails['dishes'][$i]->id}}" class="dish-quantity">0</span>
        <span class="glyphicon glyphicon-plus add" onclick="Restaurant.controllers.add('rest-{{$restaurantDetails['dishes'][$i]->id}}')"></span>
      </div>
    </div>
    @endfor
    @else
    @for(;$i<sizeof($restaurantDetails['dishes']) && $restaurantDetails['dishes'][$i]->cuisine == $restaurantDetails['dishes'][0]->cuisine;$i++)
    <div class="cuisine-list-block">
    <div class="col-xs-6 menu-item">
      @if($restaurantDetails['dishes'][$i]->veg_flag == 1)
        <img src="{{URL::asset('img/assets/Veg.png')}}" style="width:16px;margin-bottom: 4px;margin-right: 5px;">
      @else
        <img src="{{URL::asset('img/assets/NonVeg.png')}}" style="width:16px;margin-bottom: 4px;margin-right: 5px;">
      @endif
      {{$restaurantDetails['dishes'][$i]->dish_name}}
    </div>
    <div class="col-xs-2 menu-item">
      Rs. {{$restaurantDetails['dishes'][$i]->price}}
    </div>
    <div class="col-xs-4 menu-item text-right">
      <span class="glyphicon glyphicon-minus sub" onclick="Restaurant.controllers.subtract('rest-{{$restaurantDetails['dishes'][$i]->id}}')"></span>
      <span id="rest-{{$restaurantDetails['dishes'][$i]->id}}" class="dish-quantity">0</span>
      <span class="glyphicon glyphicon-plus add" onclick="Restaurant.controllers.add('rest-{{$restaurantDetails['dishes'][$i]->id}}')"></span>
    </div>
    </div>
    @endfor
    @endif
    
  </div>
  
  @endfor


            </div>
          </div>
        </div> 
  </div> <!-- End list -->
  @endif
</div>
<div class="col-md-4">
  jkdjk
</div>
</div>

<script>
    $(function(){
    // $('#city').on('change',function(e){
    //   // console.log(e);

    //   var city_id = e.target.value;

    //   $.get('restaurants/placeorder/restaurantlist/?city_id=' +city_id,function(data){
    //     $('.rest-list').empty();
    //     $.each(data,function(index,subObj){
    //       $('.rest-list').append('<option value="'+subObj.id+'">'+subObj.name+'</option>');
    //     });
    //   });
    // });
   
      $('#city').on('change', function () {
          var url = $(this).val(); 
          if (url) { 
              window.location = '{{url()}}/admin/restaurants/placeorder/'+url; 
          }
          return false;
      });
      $('#restaurant').on('change', function () {
          var rest_url = $(this).val(); 
          var city_url = $('#city').val();
          if (rest_url && city_url) { 
              window.location = '{{url()}}/admin/restaurants/placeorder/'+city_url+'/'+rest_url; 
          }
          return false;
      });

     
   
  });
</script>


@stop