@extends('layoutn.main')
@section('content')

@include('layoutn.header')
@include('sign-in-modal')
<div style="display:none" id="booking_id"></div>
<input id="lat" type="hidden"/>
<input id="lng" type="hidden"/>
<input id="city" type="hidden"/>
<div class="container" style="margin-top:25px;position: relative;">
  <div class="row">
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-3">
          <div class="rest-img">
            <img src="{{URL::asset('image/'.$restaurantDetails['details'][0]->img)}}" class="center-block" alt="Image not avaliable" style="max-height: 100%;max-width: 100%">
          </div>
        </div>
        <div class="col-md-9">
           <div class="rest-name">
             <div class="rest-heading">
               <h2 style="margin-bottom:0">{{$restaurantDetails['details'][0]->name}}</h2>
               <div class="address">@if($restaurantDetails['details'][0]->is_homely == 0)
                      <i class="fa fa-location-arrow" aria-hidden="true"></i> Address: @else  @endif {{$restaurantDetails['details'][0]->address.', '}}@if($restaurantDetails['details'][0]->is_homely == 0){{!empty($restaurantDetails['details'][0]->area_name) ? ', '.$restaurantDetails['details'][0]->area_name : '' }}@else @endif{{!empty($restaurantDetails['details'][0]->city_name) ? $restaurantDetails['details'][0]->city_name : '' }}
                </div>
               <div class="rest-cuisines">{{$restaurantDetails['details'][0]->cuisine_names}}</div>
            </div>
          <div style="float: right;margin-bottom:5px">
            <div class="veg-no-icon">
                <img src="{{URL::asset('img/assets/NonVeg.png')}}"  alt="Non Veg" title="Non Veg" data-toggle="tooltip" data-placement="top">
                <img src="{{URL::asset('img/assets/Veg.png')}}" alt="Veg" title="Veg">
            </div>
            <div class="rest-time">
              <div class="opentime">
                  <b>
                    <i>
                      <span class="glyphicon glyphicon-time"></span> Open : {{!empty($restaurantDetails['details'][0]->open_time) ? $restaurantDetails['details'][0]->open_time:'10:30 AM'}}
                    </i>
                  </b>
              </div>
              <div class="closetime">
                <b>
                  <i>
                    <span class="glyphicon glyphicon-time"></span> Close : {{!empty($restaurantDetails['details'][0]->close_time) ? $restaurantDetails['details'][0]->close_time:'10:30 PM'}}
                  </i>
                </b>
              </div>
            </div>

          </div>
       </div>
       <div class="clear text-center">
          @if(Session::has('close_status'))
          <div class="rest-closed" style="margin-top: 10px">
              {{ Session::get('close_status') }}
          </div>
          @endif
          @if(Session::has('order_value_status'))
          <div class="rest-closed" style="margin-top: 10px">
              {{ Session::get('order_value_status') }}
              &nbsp;<a href="https://play.google.com/store/apps/details?id=com.zapdel&hl=en" target="_blank">Click here to Download App&nbsp;&nbsp;<img style="height: 20px;margin-right: 10px;" src="{{asset('img/assets/androidicon.png')}}" alt=""></a>
          </div>
          @endif
       </div>
        </div>
    </div> <!-- end-->

          <div class="row">
            <div id="sidebar" class="col-md-3" style="margin-bottom: 5px;">
            <div class="cuisine-list-outer">
              <div class="filters-tab s-f-head">
                  Filter
                  <span style="float: right;"> <img src="{{URL::asset('img/assets/tool.png')}}" alt="" style="width: 16px"></span>
              </div>
               
          <div class="cuisine-list">
            <form id="frm" action="{{URl::current()}}">
             <input type="text" value="{{ csrf_token() }}" id="csrf_token" style="display: none">
            <ul class="scroll-pane">
            <?php $cusines[] = Input::has('cusines') ? Input::get('cusines') : [] ; ?>
            @for($i=0,$flag=0;$i<sizeof($restaurantDetails['cusines']);$i++)
              <li><input type="checkbox" name="cusines[]" onchange="$('#frm').submit();" value="{{$restaurantDetails['cusines'][$i]->cuisine}}" {{in_array($restaurantDetails['cusines'][$i]->cuisine, $cusines ) ? 'checked' : ''  }}><span class="filter-list-pad">{{$restaurantDetails['cusines'][$i]->cuisine}}</span></li>
              
              @endfor
            </ul>
          </form>
          </div>



          </div>
          </div>
          <div class="col-md-9">
            <div class="restaurant-menu">
              <div class="rest-menu-head">
              @if($restaurantDetails['details'][0]->is_homely == 1)
                <h3>Today's Menu</h3>
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
    </div>

    <!-- Cart Area -->
<div id="cart-bar" class="col-md-3 cartarea">
<div class="cart_container">
  <div id="cart" class="cart-outer">
    <div class="cart-head">
      <span id="cart-details-image"><img src="{{asset('/img/assets/Cuisine.png')}}" style="width:35px;margin-bottom: 5px;"></span>
      <span id="cart-details-items-quantity" style="font-size: 14px;color: #975ba5;margin-left: 10px">Your Tray
            <span id="quantity-heading">
              <div class="num-items">{{sizeof($orderDetails['dish_details'])}}</div>
             </span>
                </span>
    </div>      
        <div class="cart-list">
            @if(sizeof($orderDetails['dish_details'])>0)
            @for($i=0;$i<sizeof($orderDetails['dish_details']);$i++)
            @if($orderDetails['dish_details'][$i]->quantity>0)
                <div class="cart-items" id="cart-item-{{$orderDetails['dish_details'][$i]->rest_dish_id}}">
                    <div style="float: left;width: 180px">
                        @if($orderDetails['dish_details'][$i]->veg_flag==1)
                        <img src="{{URL::asset('img/assets/Veg.png')}}" style="width: 16px;margin-right: 5px">
                        @else
                        <img src="{{URL::asset('img/assets/NonVeg.png')}}" style="width: 16px;margin-right: 5px">
                        @endif
                        {{$orderDetails['dish_details'][$i]->dish_name}}
                    </div>
                    
                    <div class="cart-item-price" style="float: right;color: #975ba5;padding-right:5px">
                        Rs. <span id="subtotal-{{$orderDetails['dish_details'][$i]->rest_dish_id}}">{{($orderDetails['dish_details'][$i]->price)*$orderDetails['dish_details'][$i]->quantity}}</span>
                    </div>

                    <div style="clear: both;padding-left: 24px">
                        <img class="sub" style="width: 16px" src="{{ URL::asset('/img/assets/Minus.png')}}"
                             onclick="Cart.controllers.subtract({{$orderDetails['dish_details'][$i]->rest_dish_id}})">
                        <span style="margin-right: 5px;margin-left:5px" id="{{$orderDetails['dish_details'][$i]->rest_dish_id}}">{{$orderDetails['dish_details'][$i]->quantity}}</span>
                        <img class="add" style="width: 16px" src="{{ URL::asset('/img/assets/Plus.png')}}"
                             onclick="Cart.controllers.add({{$orderDetails['dish_details'][$i]->rest_dish_id}})">
                        <span style="font-size: 10px;color: #A79D9D">x Rs.{{($orderDetails['dish_details'][$i]->price)}}</span>
                    </div>
                </div>
                <div class="checkout-item-heading" id="checkout-item-heading-{{$orderDetails['dish_details'][$i]->rest_dish_id}}" style="border-top: 1px solid #c7c4c4;margin: 15px 15px;"></div>
                @endif
                @endfor

            </div>
            <div class="sub-total-div">
                <div class="cart-items" style="clear:both;color: #975ba5; font-size: 16px;">
                    <div style="float:left;font-size: 16px;margin-bottom: 10px">Item Total</div>
                    <div class="cart-item-price" style="float: right; padding-right: 18px;margin-bottom: 10px">
                        Rs. <span id="subtotal-all">{{$orderDetails['total']['total_amount']}}</span>
                    </div>
                </div>
                <div style="clear:both;margin-top:20px;background-color: #975ba5; color: #fff; text-align: center; line-height: 2.5; cursor: pointer;"
                    onclick="window.location.href='/confirm_checkout'">
                    Checkout
                </div>
                @else
                <div style="margin: 24px auto; text-align: center">
                    <img src="{{URL::asset('/img/assets/CartEmpty.png')}}">
                    <p style="margin: 8px 24px; font-size: 24px; line-height: 1.3; color: #c0c0c0;">
                        Add Something Will You ?</p>
                </div>
                @endif
            </div>
        </div>
      </div>
</div>

    </div> <!--End cart area-->
 </div>

 

</div> <!-- end container -->
<div class="fixed-cart hidden-lg hidden-md">
  <div id="cart-details-items-quantity-responsive">Your Tray
               <span><div id="quantity-heading-responsive" class="num-items-white">{{sizeof($orderDetails['dish_details'])}}</div>
               </span></div>
</div>
<script>
  $('.fixed-cart').click(function () {
    $('.cartarea').slideToggle({
      direction: "up"
    }, 300);
    

  }); // end click
</script>

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/utils.js')}}"></script>
<script src="{{URL::asset('js/mousewheel.js')}}"></script>
<script src="{{ URL::asset('js/scrollpane.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/cart.js')}}?<?=rand();?>"></script>
<script type="text/javascript" src="{{ URL::asset('js/restaurant.js')}}"></script>
<script>
  
     $(function(){
      $('#sidebar').stickit({screenMinWidth: 1280});
     
     $('#cart-bar').stickit({screenMinWidth: 1280});
     // $('.scroll-pane').jScrollPane();

      $('.s-f-head').click(function(){
        $('.cuisine-list').toggle();
    });
     
     });
    </script>
<script>
  $(function(){
       
       $( "#accordion" ).accordion({
          heightStyle: "content"
    });
     });
</script>

@stop