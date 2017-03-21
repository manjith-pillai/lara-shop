@extends('layoutn.main')
@section('content')

@include('layoutn.header')
@include('sign-in-modal')
<!-- Homepage Search Container -->
<script type="text/javascript">
        var cities = <?php echo json_encode($cities);?>
      </script>
<div class="container-fluid bg-img text-center pad">
 <h1 class="tag">Free Home Delivery Service</h1>
  <div class="container" style="padding-left: 0;padding-right:0">
    <div class="col-md-12 search-handle">
      <form action="javascript:searchRestaurants();" class="form-inline" style="margin-bottom:20px">
        <div class="form-group">
            <label class="sr-only" for="hero__name">Your Location</label>
              <select class="st" id="city-selected" onchange="citySelectedFromDropDown(this.value)">
                  <option value="" disabled hidden selected>Select City</option>
                  @foreach ($cities as $r_city)
                    <option style="padding:2px 15px" value="{{ $r_city->city_url_name}}" {{ ($user_previous_city == $r_city->city_url_name) ? 'selected' : '' }}>{{ $r_city->city_name}}</option>
                  @endforeach
              </select>
        </div>
        <div class="form-group">
        <label class="sr-only" for="hero__email"></label>
            <input class="location" type="text" id="autocomplete" placeholder="Your Location">
        </div>
        <button class="btn btn-primary search-btn" id="go_button">GO</button>
      </form>
    </div>
    <div class="col-md-12">
    <div class="app-wrapper">
    <h4 class="clear download-app">Download Zapdel App:</h4>
   <div class="google-play-store"><a href="https://play.google.com/store/apps/details?id=com.zapdel&hl=en" target="_blank"><img style="width: 110px" alt="" src="{{URL::asset('/img/assets/Google-Play-button.png')}}"></a></div>
   </div>
  </div>
  </div>

 </div>


<input id="lat" type="hidden" value="<?php if(isset($user_previous_city) && isset($cities[$user_previous_city])) echo $cities[$user_previous_city]->default_lat;?>">
    <input id="lng" type="hidden" value="<?php if(isset($user_previous_city) && isset($cities[$user_previous_city])) echo $cities[$user_previous_city]->default_lng;?>">


<div class="container">
  <div class="col-md-12">
    <div class="zp-homley">
      Zappmeal in <span class="zp-city">Noida</span>
      <a href="{{url('zappmeal')}}"><span class="zp-link">Know More</span></a>
    </div>
  </div>
</div>
<!-- End Homepage Search Container -->
<script src="{{asset('js/homescript.js')}}"></script>
 <script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete2"></script>

    <script type="text/javascript">
          window.onload=initAutocomplete2();
    </script>


    
 @stop