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
              var cityName;
              document.getElementById('lat').setAttribute("value",lat);
              document.getElementById('lng').setAttribute("value",lng);
              var latlng = new google.maps.LatLng(lat, lng);
              geocoder.geocode({'latLng': latlng}, function(results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {
                      if (results) {
                          for(var i=0; i < results.length; i++) {
                              document.getElementById('autocomplete').value = results[i].formatted_address;
                              city = results[i].formatted_address;
                              var components = results[i].address_components;
                              for (var i = 0, component; component = components[i]; i++) {
                                    if (component.types[0] == 'locality') {
                                        cityName = component['long_name'];
                                    }
                              }
                              break;
                          }
                      }
                      getCityFromLocation(city, cityName);
                  }
              });
          }

          function getCityFromLocation(location, cityName) {
            var city_found = 1;
            if(location) {
              $.each(cities, function( key, value ) {
                if(location.search(key) !=-1) {
                    document.getElementById('city-selected').value = key;
                    city_found = key;
                }
              });
              if(location.search('Greater Noida') !=-1) {
                    document.getElementById('city-selected').value = 'GreaterNoida';
                    city_found = 'GreaterNoida';
              }
            }
            if(city_found == 1 && cityName) {
              $.each(cities, function( key, value ) {
                if(cityName == cities[key].city_name) {
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