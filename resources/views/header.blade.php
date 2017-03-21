 <!-- NAVBAR
      ============================== -->
      <nav class="navbar-fixed-top">
	  <div class="navbar">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar" href="/"><img  class="zap_logo" src="/../img/assets/zap_logo.png"/><span style="margin-left: 8px"><img src="{{URL::asset('img/assets/zapdelw1.png')}}" alt="" style="max-height: 26px"></span></a>
          </div>
		  <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left ">
              <li>
                <a href="#" class="js-toggle-sidebar">
                  <span class="oi oi-menu hidden-xs"></span>
                  <span class="visible-xs">Bonus pages</span>
                </a>
              </li>
            </ul>
            
			      <ul class="nav navbar-nav navbar-right ">
              @if (Auth::guest())
                <!-- <li><a href="javascript:openlogin()" class="col-xs-5" style="text-align: right; margin-top: 0px; cursor: pointer" >SignIn</a></li> -->
                <li><a href="#" data-toggle="modal" data-target="#SignIn" class="col-xs-5" style="text-align: right; margin-top: 0px; cursor: pointer" >SignIn</a></li>
			         <!--  <li><a href="javascript:openregister()" class="col-xs-5" style="text-align: right; margin-top: 0px; cursor: pointer" >SignUp</a></li> -->
              @else
                <li style="color:#ffffff"><a href = "/">Welcome {{ Auth::user()->name }}</a></li>
                <li style="color:#ffffff ; margin-top: 5px ">|</li>
                <li><a href="/auth/logout" class="col-xs-5" style="text-align: right; margin-top: 0px; cursor: pointer" >Logout</a></li>
               @endif
            </ul>
            

          </div><!--/.nav-collapse -->
		
        </div>
		</div>
   </div>   
		

      <!-- HER ============================== -->
      <div class="hero select-city" id="home">
        <!-- Hero form -->
        <form class="form-inline hero__form" >
          <div class="container locate tray">

              <div class="col-xs-6">
                <div class="location_city">
                  <label class="sr-only" for="hero__name">Your Locat
                    ion</label>
                  <img class="locate_img" src="{{URL::asset('/img/assets/Location.png')}}">
                    <span id="city-name">{{ $city or 'Select City' }}</span>
                </div>
                <div class="toll_service">
                    <div>
                        Toll Free No. <span style="color: #975ba5">18002702707</span>
                    </div>
                    <div>
                        Service Time: 10:30 AM to 10:30 PM
                    </div>
                </div>
              </div>

              <div class="col-xs-6" id="show_cart" style="display: none">
                <div><img class="tray_img" src="{{URL::asset('/img/assets/Cuisine.png')}}" width="45px"></div>
                @if($orderDetails['dish_details'])
                <div class="your_tray"> &nbsp;&nbsp;Your Tray ( <span id="number-of-items">{{sizeof($orderDetails['dish_details'])}}</span> Items)</div>
                @else
                <div class="your_tray"> &nbsp;&nbsp;Your Tray ( <span id="number-of-items">0</span> Items)</div>
                @endif
              </div>

          </div> <!-- / .conatiner -->
        </form>

      </div> <!-- / .hero -->
	</nav>
  <!-- Modal -->
  @include('sign-in-modal')

	<div class="col-md-3" style="z-index=2;width:283px;margin-top: 100px; padding: 12px;z-index: 5;position:fixed; background-color: #F9F9F9; color: #975ba5; top: 29px;right: 65px; display: none" id="cart">

            <div style="display: inline-block;cursor: pointer;" id="cart-details-image"><img src="{{ URL::asset('/img/assets/Cuisine.png')}}" width="45px"></div>
            <div style="display: inline-block;font-size: 13px; margin-bottom: 32px;cursor: pointer; " id="cart-details-items-quantity" > &nbsp;&nbsp;Your Tray
                ( <span style="display: inline-block" id="quantity-heading">{{sizeof($orderDetails['dish_details'])}}</span> Items)</div>
            <div style="max-height: 236px; overflow-x: hidden">
                @if(sizeof($orderDetails['dish_details'])>0)
                @for($i=0;$i<sizeof($orderDetails['dish_details']);$i++)
                @if($orderDetails['dish_details'][$i]->quantity>0)
                <div class="cart-items" id="cart-item-{{$orderDetails['dish_details'][$i]->rest_dish_id}}">
                    <div class="col-md-1" style="margin:0px 15px 0px -15px">
                        @if($orderDetails['dish_details'][$i]->veg_flag==1)
                        <img src="{{URL::asset('img/assets/Veg.png')}}" style="width: 16px; margin-right: 8px">
                        @else
                        <img src="{{URL::asset('img/assets/NonVeg.png')}}" style="width: 16px; margin-right: 8px">
                        @endif
                    </div>
                    <p>{{$orderDetails['dish_details'][$i]->dish_name}}</p>
                    <div class="cart-item-price" >
                        Rs. <span style="display: inline-block" id="subtotal-{{$orderDetails['dish_details'][$i]->rest_dish_id}}">{{($orderDetails['dish_details'][$i]->price)*$orderDetails['dish_details'][$i]->quantity}}</span>
                    </div>

                    <div class="col-md-9" style="margin-left: 5%">
                        <img src="{{ URL::asset('/img/assets/Minus.png')}}" style="width: 16px; margin-right: 4px; cursor: pointer"
                             onclick="Cart.controllers.subtract({{$orderDetails['dish_details'][$i]->rest_dish_id}})">
                        <span style="display: inline-block" id="{{$orderDetails['dish_details'][$i]->rest_dish_id}}">{{$orderDetails['dish_details'][$i]->quantity}}</span>
                        <img src="{{ URL::asset('/img/assets/Plus.png')}}" style="width: 16px; margin-left: 4px;cursor: pointer"
                             onclick="Cart.controllers.add({{$orderDetails['dish_details'][$i]->rest_dish_id}})">
                        <span style="font-size: 10px;color: #A79D9D">x Rs.{{($orderDetails['dish_details'][$i]->price)}}</span>
                    </div>
                </div><br>
                <div class="checkout-item-heading" id="checkout-item-heading-{{$orderDetails['dish_details'][$i]->rest_dish_id}}" style="border-top: 1px solid #C7C4C4;position: relative; top: 8px; margin: 0 15px 0 15px"></div>
                @endif
                @endfor
            </div>
            <div class="col-md-12" style="margin-top: 16px;">
                <div class="cart-items">
                    <p style="color: #909090">Sub Total </p>
                    <div class="cart-item-price">
                        Rs. <span id="subtotal-all">{{$orderDetails['total']['total_amount']}}</span>
                    </div>
                </div>
                <div class="cart-items" style="color: #975ba5; font-size: 16px; padding:0px 0 8px 0; ">
                    <p style="font-size: 16px;">Total </p>
                    <div class="cart-item-price">
                        Rs. <span id="total-all">{{$orderDetails['total']['total_amount']}}</span>
                    </div>
                </div>
                <div style="background-color: #975ba5; color: #fff; text-align: center; line-height: 2.5; cursor: pointer;"
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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{URL::asset('js/auth.js')}}"></script>
    <script src="{{asset('js/google-analytics.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() 
        {
          
          @if (!Auth::guest())
          
            var location = window.location ;
            var previous = '{{ URL::previous() }}' ;

            var pos = previous.indexOf('/auth/login');
            var pos1 = previous.indexOf('/auth/register');
            if(pos === -1)
            {
              window.open('','_parent','');
              window.close();
              window.top.close();
            }
            else if(pos1 === -1)
            {
              window.open('','_parent','');
              window.close();
              window.top.close();
            }
          @endif
      });

      function openlogin()
      {
          var child = window.open('/auth/login','login','width=300,height=400') ;
          addReload(child);
      }

      function openregister()
      {
          var child = window.open('/auth/register','register','width=300,height=400') ;
          addReload(child);
      }

      function  addReload(child)
      {
          var  interval = setInterval(function()
          {
              if(null != child)
              {
                 if(child.closed)
                 {
                    child = null ;
                    clearInterval(interval) ;
                    location.reload();
                  }
              }
              else
              {
                
              }
          }, 1000) ;          
      }

</script>
		
