	<h2 style="font-size:40px;font-weight: lighter;">Menu</h2>
	<div id="restaurant_menu" style="border-top: 1px solid #f2f2f4;position: relative;margin-top: -6px;"></div>
	@for($i=0,$flag=0;$i<sizeof($restaurantDetails['dishes']);$flag=1)
	<div class="col-md-12 menu-page-cuisine">
		<h2>{{$restaurantDetails['dishes'][$i]->cuisine}}</h2>
		@if($i!=0)
		@for(; $i<sizeof($restaurantDetails['dishes']) && ($restaurantDetails['dishes'][$i]->cuisine == $restaurantDetails['dishes'][$i-1]->cuisine || $flag==1);$i++,$flag=0)
			<div class="col-xs-6 pad-top-24 menu-item">
				@if($restaurantDetails['dishes'][$i]->veg_flag == 1)
					<img src="{{URL::asset('img/assets/Veg.png')}}" style="width: 16px; margin-right: 32px">
				@else
					<img src="{{URL::asset('img/assets/NonVeg.png')}}" style="width: 16px; margin-right: 32px">
				@endif
				{{$restaurantDetails['dishes'][$i]->dish_name}}
			</div>
			<div class="col-sm-2 menu-item dish-price">
				Rs. {{$restaurantDetails['dishes'][$i]->price}}
			</div>

			<div class="col-sm-2 menu-item">
				<span class="glyphicon glyphicon-minus" onclick="Restaurant.controllers.subtract('rest-{{$restaurantDetails['dishes'][$i]->id}}')"></span>
				<span id="rest-{{$restaurantDetails['dishes'][$i]->id}}" class="dish-quantity">0</span>
				<span class="glyphicon glyphicon-plus" onclick="Restaurant.controllers.add('rest-{{$restaurantDetails['dishes'][$i]->id}}')"></span>
			</div>
		@endfor
		@else
		@for(;$i<sizeof($restaurantDetails['dishes']) && $restaurantDetails['dishes'][$i]->cuisine == $restaurantDetails['dishes'][0]->cuisine;$i++)
		<div class="col-xs-6 pad-top-24 menu-item">
			@if($restaurantDetails['dishes'][$i]->veg_flag == 1)
				<img src="{{URL::asset('img/assets/Veg.png')}}" style="width: 16px; margin-right: 32px">
			@else
				<img src="{{URL::asset('img/assets/NonVeg.png')}}" style="width: 16px; margin-right: 32px">
			@endif
			{{$restaurantDetails['dishes'][$i]->dish_name}}
		</div>
		<div class="col-sm-2 menu-item dish-price">
			Rs. {{$restaurantDetails['dishes'][$i]->price}}
		</div>
		<div class="col-sm-2 menu-item">
			<span class="glyphicon glyphicon-minus" onclick="Restaurant.controllers.subtract('rest-{{$restaurantDetails['dishes'][$i]->id}}')"></span>
			<span id="rest-{{$restaurantDetails['dishes'][$i]->id}}" class="dish-quantity">0</span>
			<span class="glyphicon glyphicon-plus" onclick="Restaurant.controllers.add('rest-{{$restaurantDetails['dishes'][$i]->id}}')"></span>
		</div>
		@endfor
		@endif

	</div>
	@endfor
</div>
			