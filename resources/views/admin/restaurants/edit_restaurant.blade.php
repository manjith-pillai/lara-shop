@extends('admin.dashboard.layouts.home')
@section('admin_content')
<div class="container-fluid" style="margin-bottom: 30px">
<ol class="breadcrumb">
      <li><a href="{{ url('admin/restaurants/index') }}">Restaurant List</a></li>
      <li class="active">Restaurant: {{ $restaurant->name }} - {{$restaurant->address}}</li>
  </ol>
@if(Session::has('flash_message'))
    <div class="alert alert-success" style="margin-top: 10px">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_message') }}
    </div>
@endif
@if ($errors->has('area_name')) 
<div class="alert alert-danger" style="margin-top: 10px">
  {{ $errors->first('area_name') }} Please enter New Area.
</div>
@endif
<h1>Update Restaurant</h1>
<hr>
<div class="row">
<form method="POST" action="{{url('admin/restaurants/update/'.$restaurant->id)}}" enctype="multipart/form-data">
  <input type="hidden" name="_method" value="PATCH">
<div class="col-md-6 col-xs-12">


  
  <div class="form-group form-gp-bg @if ($errors->has('name')) has-error @endif">
    <label for="restaurant_name">Restaurant Name</label>
    <input type="text" value="{{ $restaurant->name }}" class="form-control" id="restaurant_name" placeholder="Restaurant Name" name="name">
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
  </div>
  <div class="form-group form-gp-bg @if ($errors->has('address')) has-error @endif">
    <label for="restaurant_address">Restaurant Address</label>
    <input type="text" value="{{ $restaurant->address }}" class="form-control" id="restaurant_name" placeholder="Restaurant Address" name="address">
    @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
  </div>
    <div class="form-group form-gp-bg @if ($errors->has('img')) has-error @endif">
    <label for="upload-image">Upload Restaurant Image</label>
                <input type="file" name="img" id="upload-image">
                 @if ($errors->has('img')) <p class="help-block">{{ $errors->first('img') }}</p> @endif
    </div>
    <div><img style="height:80px;width:100px;margin-bottom:5px" src="{{asset('/image/'.$restaurant->img)}}" alt=""></div>       
   <div class="form-group form-gp-bg @if ($errors->has('city_id')) has-error @endif">
  <label for="city">City:</label>
  <select class="form-control" id="city" name="city_id">
  @foreach ($cities as $r_city)
   
    <option value="{{$r_city->city_id}}" {{ ($r_city->city_id == $restaurant->city_id) ? 'selected' : '' }}>{{$r_city->city_name}}</option>
    @endforeach
  </select>
   @if ($errors->has('city_id')) <p class="help-block">{{ $errors->first('city_id') }}</p> @endif
</div>
 <div class="form-group form-gp-bg @if ($errors->has('area_id')) has-error @endif">
  <label for="city">Area:</label>
  <select class="form-control area_list" id="area" name="area_id">
  @foreach ($area as $a)
    <option value="{{$a->area_id}}" {{ ($a->area_id == $restaurant->area_id) ? 'selected' : '' }} >{{$a->name}}</option>
    @endforeach
  </select>
   @if ($errors->has('area_id')) <p class="help-block">{{ $errors->first('area_id') }}</p> @endif
</div>
<div class="addArea"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Area</div>
<div class="form-group form-gp-bg" id="new_area" style="display: none;">
  <input type="text" value="" class="form-control" id="" placeholder="Add Area" name="area_name">
</div>

   <div class="form-group form-gp-bg @if ($errors->has('phone')) has-error @endif">
    <label for="phone_number">Restaurant Phone Number</label>
    <input type="text" value="{{ $restaurant->phone }}" class="form-control" id="phone_number" placeholder="Restaurant Phone Number" name="phone">
    @if ($errors->has('phone')) <p class="help-block">{{ $errors->first('phone') }}</p> @endif
  </div>
   <div class="form-group form-gp-bg @if ($errors->has('email')) has-error @endif">
    <label for="e-mail">E-mail Address</label>
    <input type="e-mail" value="{{ $restaurant->email }}" class="form-control" id="e-mail" placeholder="Email Address" name="email">
     @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
  </div>
  <div class="form-group form-gp-bg @if ($errors->has('cost_two_people')) has-error @endif">
    <label for="cost_for_two">Cost For Two</label>
    <input type="text" value="{{ $restaurant->cost_two_people }}" class="form-control" id="cost_for_two" placeholder="Cost For Two" name="cost_two_people">
     @if ($errors->has('cost_two_people')) <p class="help-block">{{ $errors->first('cost_two_people') }}</p> @endif
  </div>
 
  <div class="form-group form-gp-bg">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
          <label for="">Open Time</label>
          <input type="text" value="{{ $restaurant->open_time }}" class="form-control timepick"  placeholder="" name="open_time" >
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
          <label for="">Close Time</label>
          <input type="text" value="{{ $restaurant->close_time }}" class="form-control timepick"  placeholder="" name="close_time">
          </div>
        </div>
        </div>
  </div>
  <div class="form-group form-gp-bg @if ($errors->has('lng')) has-error @endif">
    <label for="longitude">Longitude</label>
    <input type="text" value="{{ $restaurant->lng }}" class="form-control" id="longitude" placeholder="Longitude" name="lng">
    @if ($errors->has('lng')) <p class="help-block">{{ $errors->first('lng') }}</p> @endif
  </div>
   <div class="form-group form-gp-bg @if ($errors->has('lat')) has-error @endif">
    <label for="latitude">Latitude</label>
    <input type="text" value="{{ $restaurant->lat }}" class="form-control" id="Latitude" placeholder="Latitude" name="lat">
    @if ($errors->has('lat')) <p class="help-block">{{ $errors->first('lat') }}</p> @endif
  </div>
  <div class="form-group form-gp-bg @if ($errors->has('contact_person')) has-error @endif">
    <label for="contact_person">Contact Person</label>
    <input type="text" value="{{ $restaurant->contact_person }}" class="form-control" id="contact_person" placeholder="Contact Person" name="contact_person">
    @if ($errors->has('contact_person')) <p class="help-block">{{ $errors->first('contact_person') }}</p> @endif
  </div>
   </div>
  <div class="col-md-6 col-xs-12">
    
  <div class="form-group form-gp-bg @if ($errors->has('contact_person_phone')) has-error @endif">
    <label for="contact_person_phone">Contact Person Phone No.</label>
    <input type="text" value="{{$restaurant->contact_person_phone}}" class="form-control" id="contact_person_number" placeholder="Contact Person Phone No." name="contact_person_phone">
    @if ($errors->has('contact_person_phone')) <p class="help-block">{{ $errors->first('contact_person_phone') }}</p> @endif
  </div>

    <div class="form-group form-gp-bg">
    <label>Tax:</label>
        <div class="form-group @if ($errors->has('service_tax_percent')) has-error @endif">
          <div class="row">
            <div class="col-md-6">
              <label for="">Service Tax & CESS(%)</label>
              <input type="text" class="form-control" name="service_tax_percent" placeholder="0.00" value="{{ $restaurant->service_tax_percent }}">
            </div>
             <div class="col-md-6">
                <label for="">VAT(%)</label>
                <input type="text" class="form-control" name="vat_percent" placeholder="0.00" value="{{ $restaurant->vat_percent }}">
            </div>
            <div class="col-md-6" style="margin-top: 5px">
               <label for="">Service charge(%)</label>
                <input type="text" class="form-control" name="service_charge_percent" placeholder="0.00" value="{{ $restaurant->service_charge_percent }}">
            </div>
            <div class="col-md-6" style="margin-top: 5px">
              <label for="">Packaging charge(Rs.)</label>
               <input type="text" class="form-control" name="packaging_charge" placeholder="0.0" style="margin-top: 5px" value="{{ $restaurant->packaging_charge }}">
            </div>
          </div>
           
          @if ($errors->has('service_tax_percent')) <p class="help-block">{{ $errors->first('service_tax_percent') }}</p> @endif   
        </div> 
    </div>

<div class="form-group form-gp-bg @if ($errors->has('rating')) has-error @endif">
  <label for="ratings">Ratings:</label>
  <select class="form-control" id="ratings" name="rating">
    <option value="1" {{$restaurant->rating == 1 ? 'selected':''}}>1</option>
    <option value="2" {{$restaurant->rating == 2 ? 'selected':''}}>2</option>
    <option value="3" {{$restaurant->rating == 3 ? 'selected':''}}>3</option>
    <option value="4" {{$restaurant->rating == 4 ? 'selected':''}}>4</option>
    <option value="5" {{$restaurant->rating == 5 ? 'selected':''}}>5</option>
  </select>
  @if ($errors->has('rating')) <p class="help-block">{{ $errors->first('rating') }}</p> @endif
  </div>

  <div class="form-group form-gp-bg @if ($errors->has('featured')) has-error @endif">
  <label for="featured">Featured:</label>
  <select class="form-control" id="featured" name="featured">
    <option value="0" {{$restaurant->featured == 0 ? 'selected':''}}>Yes</option>
    <option value="1" {{$restaurant->featured == 1 ? 'selected':''}}>No</option>
  </select>
  @if ($errors->has('featured')) <p class="help-block">{{ $errors->first('featured') }}</p> @endif
  </div>
  <div class="form-group form-gp-bg @if ($errors->has('is_active')) has-error @endif">
  <label for="active">Active:</label>
  <select class="form-control" id="active" name="is_active">
    <option>Open</option>
    <option>Closed</option>
  </select>
   @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
  </div>
   <div class="form-group form-gp-bg @if ($errors->has('resturant_cuisine')) has-error @endif">
  <label>Cuisines:</label>
  <div>
  <ul class="t">
    @foreach($cuisine as $cuisine_list) 

     
        <li><input type="checkbox" {{in_array($cuisine_list->cuisine_id,$cuisinelist) ? 'checked': ''}} class="" value="{{$cuisine_list->cuisine_id}}" name="resturant_cuisine[]" id="cuisine"> {{$cuisine_list->cuisine_name}} </li>
     
      
    @endforeach
 </ul>
    </div>
   @if ($errors->has('resturant_cuisine')) <p class="help-block">{{ $errors->first('resturant_cuisine') }}</p> @endif
  </div>

   <div class="form-group form-gp-bg">
    <label for="meta_keywords">Meta-Keywords</label>
    <textarea name="meta_keywords" id="meta_keywords" class="form-control" style="max-width: 100%">{{ $restaurant->meta_keywords }}</textarea>
  </div>
   <div class="form-group form-gp-bg">
    <label for="meta_description">Meta-Description</label>
    <textarea name="meta_description" id="meta_description" class="form-control" style="max-width: 100%">{{ $restaurant->meta_description }}</textarea>
  </div>
 </div>
 <div style="clear: both;"></div>
 <div class="col-md-12 text-center">
   <button type="submit" class="btn btn-default">Update Restaurant</button>
 </div>
 
</form>
</div>
<!-- End -->

</div>
<script>
    $(function(){
    $('#city').on('change',function(e){
      console.log(e);

      var city_id = e.target.value;

      $.get('{id}/area?city_id=' +city_id,function(data){
        $('.area_list').empty();
        $.each(data,function(index,subObj){
          $('.area_list').append('<option value="'+subObj.area_id+'">'+subObj.name+'</option>');
        });
      });
    });
  });
</script>
</script>
<script>
   $(function(){
       $(".addArea").click(function(){
          $("#new_area").show();

    });
       $(".timepick").timepicki();
});
</script>
@stop