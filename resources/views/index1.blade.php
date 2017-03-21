<!DOCTYPE html>
<html>
<head>
    <title>Zapdel</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
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
            position: absolute;
            top: 0px;
            left: 0px;
            width: 99%;
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

</head>

<body onload="geolocate()">
<br><br><br><br>
<div id="locationField">
    <input id="autocomplete" placeholder="Enter your address" type="text"><br><br>
    <input id="search" placeholder="Search for restaurant or cuisine" type="text">
    <input id="lat" value="" style="display:block" type="text">
    <input id="lng" value="" style="display:block" type="text">
</div>
<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete"></script>
<script>
    var placeSearch, autocomplete,lat,lng,geocoder;

    function initAutocomplete() {

        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        var place = autocomplete.getPlace();
        lat=place.geometry.location.lat();
        lng=place.geometry.location.lng();
        codeLatLng(lat, lng);
        console.log(lat+','+lng);
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

    function codeLatLng(lat, lng) {
        alert('here');
        document.getElementById('lat').value=lat;
        document.getElementById('lng').value=lng;
        var latlng = new google.maps.LatLng(lat, lng);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    for(var i=0;i<results.length;i++){
                        if(results[i].geometry.location_type!="ROOFTOP")
                        {
                            document.getElementById('autocomplete').value=results[i].formatted_address;
                            break;
                        }
                    }
                }
            }
        });
        console.log(lat+','+lng);
    }
</script>

<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="js/app.js?<?=rand();?>"></script>
</body>
</html>