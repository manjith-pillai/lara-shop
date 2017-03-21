@extends('admin.dashboard.layouts.home')
@section('admin_content')
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="row">
           <div class="col-md-8">
               <h1>Track Delivery Boys</h1>
           </div>
           <div class="col-md-4">
            <div class="st-city">

              <fieldset class="form-group">
                <label for="select-city">Select City: </label>
                  <select class="form-control" id="select-city">
                   @foreach ($cities as $r_city)
                    <option value="{{$r_city->city_url_name}}" {{($r_city->city_url_name == $city_url_name) ? 'selected' : '' }}>{{$r_city->city_name}}</option>
                    @endforeach
                  </select>
            </fieldset>

          </div>
          </div> 
        </div>
        <div class="container">
            <hr>
            <div id="map"></div>
        </div>
    </div>
</div>
<style>
#map {
  height: 500px;
}
</style>
<script>
    $(function() {
      $('#select-city').on('change', function () {
          var url = $(this).val(); 
          if (url) { 
              window.location = '{{url()}}/admin/'+url+'/track_boys'; 
          }
          return false;
      });
    });
</script>
<script>

      var boys_locations = <?php echo json_encode($boys_locations);?>;
      //var center = new google.maps.LatLng(28, 77);

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: {lat: <?php echo $cityLocation->default_lat; ?> , lng: <?php echo $cityLocation->default_lng; ?>}
        });

        var image = '{{URL::asset('/img/assets/delivery-boy-32-32.png')}}';
        var infowindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder;
        var marker, i;
        var markers = [];
        
        var bounds = new google.maps.LatLngBounds();
        for (i = 0; i < boys_locations.length; i++) {  
           marker = new google.maps.Marker({
             position: new google.maps.LatLng(boys_locations[i].latitude, boys_locations[i].longitude),
             map: map,
             icon: image
           });
           markers.push(marker);
           google.maps.event.addListener(marker, 'click', (function(marker, i) {
             return function() {
               //infowindow.setContent(boys_locations[i].name+', '+boys_locations[i].phone);
               //infowindow.open(map, marker);
               //for geocode
               var boy_latlng = {lat: parseFloat(boys_locations[i].latitude), lng: parseFloat(boys_locations[i].longitude)};
               //var boy_latlng = {lat: 28.5815483, lng: 77.318439};
                geocoder.geocode({'location': boy_latlng}, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                      if (results[1]) {
                        infowindow.setContent(boys_locations[i].name+', '+boys_locations[i].phone+', <br/>'+results[1].formatted_address);
                        infowindow.open(map, marker);
                      } else {
                        infowindow.setContent(boys_locations[i].name+', '+boys_locations[i].phone);
                        infowindow.open(map, marker);
                      }
                    } else {
                      window.alert('Geocoder failed due to: ' + status);
                    }
               });
               
             }
           })(marker, i));
           var locationPoint = new google.maps.LatLng(boys_locations[i].latitude, boys_locations[i].longitude);
            bounds.extend(locationPoint);
         }
        if(boys_locations.length) { 
            var markerCluster = new MarkerClusterer(map, markers, {imagePath: '{{URL::asset('/img/assets/m/m')}}'});
            map.fitBounds(bounds);
        }
      }
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRplMwQFzPFyZDgLhmbwrOksVzkuLt-Vo&callback=initMap">
</script>
<script type="text/javascript" src="https://googlemaps.github.io/js-marker-clusterer/src/markerclusterer.js"></script>
@stop
