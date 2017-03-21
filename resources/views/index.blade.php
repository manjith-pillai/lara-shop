<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="google" content="notranslate" />
    <link rel="icon" href="ico/favicon.ico">
    <title>Zapdel</title>
    <!-- CSS Plugins -->
    <link href="css/plugins/animate.css" rel="stylesheet">
    <link href="css/plugins/owl.carousel.css" rel="stylesheet">
    <link href="css/plugins/lightbox.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <!--link href="fonts/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    <link href="fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Google Fonts -->

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Karla:400,700' rel='stylesheet' type='text/css'>
    <!-- CSS Custom -->
    <link href="css/styles.css" rel="stylesheet">
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
        }
    </style>
    <link type="text/css" rel="stylesheet">
    <style>
          #locationField, #controls {
              position: relative;
              width: 480px;
          }
          #autocomplete {
              /*position: absolute;*/
              /*top: 0px;*/
              /*left: 0px;*/
              width: 300px;
          }
          .label {
              text-align: right;
              font-weight: bold;
              width: 100px;
              color: #303030;
          }
          #address {
              border: 1px solid #000090;
              background-color: #f0f0ff;
              width: 480px;
              padding-right: 2px;
          }
          #address td {
              font-size: 10pt;
          }
          .field {
              width: 99%;
          }
          .slimField {
              width: 80px;
          }
          .wideField {
              width: 200px;
          }
          #locationField {
              height: 20px;
              margin-bottom: 2px;
          }
      </style>
      <script type="text/javascript">
        var cities = <?php echo json_encode($cities);?>
      </script>

  </head>

  <body data-spy="scroll" data-target=".navbar" data-offset="71">
    <!-- WRAPPER
    ============================== -->
    <div id="home" class="wrapper">
		@include('header')
		<div class="hero hero_sign-up" id="home1"  >
          <div class="form-inline hero__form" style="background-image: url({{URL::asset('/img/assets/LandingPageMainImage.jpg')}}); top: -190px; background-repeat: no-repeat; background-size: 100%; height: 190%; ">
              <div class="container">
                <div class="hint_search">
                <h1 style="font-weight:bold;">Free Home Delivery Service</h1>
              </div>
                  <div class="row search-restro">
                      <div class="col-xs-12">
                      <form action="javascript:searchRestaurants();">
                          <div class="form-group st">
                              <label class="sr-only" for="hero__name">Your Location</label>
                              <select class="" id="city-selected" onchange="citySelectedFromDropDown(this.value)">
                                  <option value="" disabled hidden selected>Select City</option>
                                  @foreach ($cities as $r_city)
                                  <option value="{{ $r_city->city_url_name}}" {{ ($user_previous_city == $r_city->city_url_name) ? 'selected' : '' }}>{{ $r_city->city_name}}</option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="form-group">
                              <label class="sr-only" for="hero__email"></label>
                              <input type="text" class="form-control input-lg" id="autocomplete" placeholder="Your Location">
                          </div>
                          <button class="btn btn-primary" onclick="searchRestaurants()" id="go_button">GO</button>
                      </div>
                      </form>
                  </div> <!-- / .row -->

              </div> <!-- / .conatiner -->
          </div>
        </div>
<!--      </div> <!-- / .section -->

      @include('footer')
	</div> <!-- / .wrapper -->
    <input id="lat" type="hidden">
    <input id="lng" type="hidden">

    <!-- JavaScript
    ================================================== -->
    <!-- JS Global -->
    <script src="../js/plugins/jquery.min.js"></script>
    <script src="../js/bootstrap/bootstrap.min.js"></script>

    <!-- JS Plugins -->
    <script src="../js/plugins/smoothscroll.js"></script>
    <script src="../js/plugins/jquery.waypoints.min.js"></script>
    <script src="../js/plugins/wow.min.js"></script>
    <script src="../js/plugins/owl.carousel.min.js"></script>
    <script src="../js/plugins/jquery.peity.min.js"></script>
    <script src="../js/plugins/lightbox.min.js"></script>
    <script src="{{asset('js/google-analytics.js')}}"></script>

    <!-- JS Custom -->
    <script src="../js/custom.js"></script>
    <script type="text/javascript" src="../js/utils.js"></script>
    <script type="text/javascript" src="../js/app.js?<?=rand();?>"></script>
    <script type="text/javascript" src="../js/cart.js"></script>
    <script type="text/javascript" src="../js/restaurant.js"></script>
	<script type="text/javascript" src="../js/personal_details.js"></script>
	<script type="text/javascript" src="{{ URL::asset('js/personal_details.js')}}?<?=rand();?>"></script>

    <script type="text/javascript">
          var placeSearch, autocomplete,lat,lng,geocoder;

          function initAutocomplete2() {
              geolocate();
              var documentList = document.getElementById('autocomplete') ;
              autocomplete = new google.maps.places.Autocomplete(documentList,
                  {types: ['geocode']},{componentRestrictions: {country: ["in"]}});
              autocomplete.addListener('place_changed', fillInAddress);
          }

          function fillInAddress() {
              var place = autocomplete.getPlace();
              lat=place.geometry.location.lat();
              lng=place.geometry.location.lng();
              codeLatLng(lat, lng);
          }

          function codeLatLng(lat, lng) {
              var city;
              document.getElementById('lat').setAttribute("value",lat);
              document.getElementById('lng').setAttribute("value",lng);
              var latlng = new google.maps.LatLng(lat, lng);
              geocoder.geocode({'latLng': latlng}, function(results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {
                      if (results[1]) {
                          for(var i=0;i<results.length;i++){
                              if(results[i].geometry.location_type!="ROOFTOP")
                              {
                                  document.getElementById('autocomplete').value=results[i].formatted_address;
                                  city=results[i].formatted_address;
                                  break;
                              }
                          }
                      }
                      getCityFromLocation(city);
                  }
              });
          }

          function getCityFromLocation(location) {
            var city_found = 1;
            if(location) {
              $.each( cities, function( key, value ) {
                if(location.search(key) !=-1) {
                    document.getElementById('city-selected').value = key;
                    city_found = key;
                }
              });
            }
            if(city_found ==1) {
                alert("Oops! Sorry your area is currently out of our service area ");
                document.getElementById('autocomplete').value ='' ;
            } else {
              changeCity(city_found, 0);
            }
          }


          function changeCity(city, update_lat_log) {
            update_lat_log = typeof update_lat_log !== 'undefined' ? update_lat_log : 1;
            if(city) {
              document.getElementById('city-name').innerHTML = cities[city].city_name;
              if(update_lat_log) {
                document.getElementById('lat').setAttribute("value",cities[city].default_lat);
                document.getElementById('lng').setAttribute("value",cities[city].default_lng);  
              }
              
            } 
          }


           function searchRestaurants() {
              var city= $('#city-selected').val();
              if(city) {
                var lat = document.getElementById('lat').value;
                var lng = document.getElementById('lng').value;
                lat=lat.replace('.','@');
                lng=lng.replace('.','@');
                if(lat=='' && lng=='')
                  alert("latitude and  longitude are not available ");
                var url="/"+city+'/search/restaurant/'+lat+'/'+lng;
                url = url.replace(/\s/g, '');
                location.assign(url);
             } else {
              alert('Please select city');
            }
          }
		  
          function geolocate() {
              geocoder = new google.maps.Geocoder;
              if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(function(position) {
                      lat= position.coords.latitude;
                      lng= position.coords.longitude;
                      codeLatLng(lat,lng);
                  });
              }
          }

           function citySelectedFromDropDown(selected_city) {
            if(selected_city != "") {
              document.getElementById('autocomplete').removeAttribute("disabled");
              document.getElementById('go_button').removeAttribute("disabled");
              document.getElementById('autocomplete').value ='' ;
              var date = new Date();
              date.setTime(date.getTime()+(30*24*60*60*1000));
              var expires = "; expires="+date.toGMTString();
              document.cookie = "user_city="+selected_city+expires+"; path=/";
            }
            changeCity(selected_city, 1);
          }


          function readCookie(name) {
              var nameEQ = name + "=";
              var ca = document.cookie.split(';');
              for(var i=0;i < ca.length;i++) {
                  var c = ca[i];
                  while (c.charAt(0)==' ') c = c.substring(1,c.length);
                  if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
              }
              return null;
          }
          console.log(readCookie('user_city'));

      </script>
    <script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete2"></script>

    <script type="text/javascript">
          window.onload=initAutocomplete2();
    </script>
    
  </body>
</html>
