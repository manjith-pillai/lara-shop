@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
    <h1>Sitemap</h1>
    <hr>
     <img src="{{URL::asset('/img/assets/india.png')}}" style="margin-bottom: 10px;
    width: 35px;" alt="india" title="India">
     <div style="display: inline-block;font-size: 24px;margin-left: 5px;">India</div>

        @foreach(array_chunk($cities,3) as $row)
            <div class="row">
                @foreach($row as $city)
                   <div class="col-md-4">
                       <h3>{{ $city->city_name }}</h3>
                        @foreach($city->restaurants as $restaurant)
                     <a href="{{url($city->city_url_name.'/'.$restaurant->url_name)}}">
                            <h6>{{ $restaurant->name }}</h6>
                        </a>
                @endforeach
                   </div> 
                    
                @endforeach

            </div>

        @endforeach

</div>
@stop
