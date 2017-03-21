<!-- Header -->
<header class="container" style="padding-top:60px">
  <div class="row">
    <div class="col-md-12">
      <div class="location_city">
                  <label class="sr-only" for="hero__name">Your Location</label>
                  <img class="locate_img" src="{{URL::asset('/img/assets/Location.png')}}">
                    <div id="city-name" class="city"><?php if(!empty($city)) {

                      } else if(!empty($user_previous_city) && isset($cities[$user_previous_city])) {
                          $city = $cities[$user_previous_city]->city_name;
                      }
                    ?>{{ $city or 'Select City' }}</div>
                </div>
                <div class="toll-services">
                    <div>
                        Toll Free No. <span style="color: #975ba5">18002702707</span>
                    </div>
                    <div>
                        Service Time: 10:30 AM to 10:30 PM
                    </div>
                </div>
    </div>
    
    
  </div>

        <div id="show_cart" style="display: none">
                <div><img class="tray_img" src="{{URL::asset('/img/assets/Cuisine.png')}}" width="45px"></div>
                @if($orderDetails['dish_details'])
                <div class="your_tray"> &nbsp;&nbsp;Your Tray ( <span id="number-of-items">{{sizeof($orderDetails['dish_details'])}}</span> Items)</div>
                @else
                <div class="your_tray"> &nbsp;&nbsp;Your Tray ( <span id="number-of-items">0</span> Items)</div>
                @endif
        </div>

</header>
<!-- End Header -->