<?php
/**
 * User: Rishabh
 * Date: 10/11/15
 * Time: 2:30 AM
 */
?>
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
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <!-- CSS Plugins -->
    <link href="{{ URL::asset('css/plugins/animate.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('css/plugins/owl.carousel.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('css/plugins/lightbox.css')}}" rel="stylesheet">
    <!--link href="fonts/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    <link href="fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Google Fonts --
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Karla:400,700' rel='stylesheet' type='text/css'>

    <!-- CSS Custom -->
    <link href="{{ URL::asset('css/styles.css') }}" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <!--script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <link type="text/css" rel="stylesheet">
    <script src="{{asset('js/hotjar.js')}}"></script>

</head>

<body data-spy="scroll" data-target=".navbar" data-offset="71">
<div style="display:none" id="booking_id"></div>

<!-- WRAPPER
============================== -->
<div class="wrapper">

<!-- NAVBAR
============================== -->

@include('page-header');


<div class="container">
    <div class="row" style="margin-top: 110px">
        <div class="col-xs-pull-12">

            <div class="col-xs-3 checkout-next-step" style="color: #45BA7E">
                <img src="{{URL::asset('img/assets/Done.png')}}" style="width: 30px">
                <div style="display: inline-block;padding-top: 2px;">Confirm Checkout</div>
            </div>
            
            <div class="col-xs-4 checkout-selected">
                <div style="border-radius:100%; border:2px solid #975ba5;height: 45px; width: 45px;margin-left: 56px;padding-top: 8px ">
                    2
                </div>
                <div style="display: inline-block; top:-35px; position: relative;margin-left: 100px">
                    Provide Your Details
                </div>
                <!-- <svg>
                     <circle cx="25" cy="25" r="20" stroke="#975ba5" fill="white" stroke-width="2"/>
                 </svg>-->
            </div>

           <!--  <div class="col-xs-3 checkout-next-step">
                <div style="border-radius:100%; border:2px solid #c0c0c0;height: 35px; width: 35px;margin-left: 20px;color:#c0c0c0;padding-top: 6px">
                    <p style="margin: 2px;">3</p>
                </div>
                <div style="display: inline-block; top:-26px; position: relative">
                    Verify and Place
                </div>
            </div> -->
        </div>
    </div>
</div>
<input type="hidden" id="number-of-items" value="{{sizeof($orderDetails['dish_details'])}}"/>
<div class="container">
    <div class="row" style="margin-left: 2%">
        <div class="col-md-9 user-details" style="margin-top: 24px">
            <div class="section">
                <h2>Personal Details</h2>
                <label>Full Name &nbsp;<span class="mandatory-field">*</span></label><br>
                <input class="checkout-item-input" style="width: 40%; min-width: 240px" placeholder="Enter your Name" id="name"><br><br>

                <label>Email ID &nbsp;<span class="mandatory-field">*</span></label><br>
                <input class="checkout-item-input" style="width: 40%; min-width: 240px" placeholder="Enter your Email ID" id="email"><br><br>
                @if($errors->has('email'))
                    <ul class="alert alert-danger-custom">
                        <li>
                            Please enter a Valid email address.
                        </li>
                    </ul>
                @endif
                <label>Mobile &nbsp;<span class="mandatory-field">*</span></label><br>
                <input class="checkout-item-input" style="width: 40px;padding: 6px" value="+91" readonly>&nbsp;
                <input class="checkout-item-input" style="width: 34%; min-width: 190px" placeholder="Enter your Mobile No." id="phone" maxlength="10"><br>
                <!--<p style="color:#909090; margin-left: 54px" >You will recieve verification code on this number</p>-->
                @if($errors->has('phone'))
                    <ul class="alert alert-danger-custom">
                        <li>
                            Your phone number must contain 10 characters and should be numeric.
                        </li>
                    </ul>
                @endif
            </div>
        </div>
        
        <div class="col-md-9 user-details" style="margin-top: 0px">
            <div class="section">
                <h2 style="margin-top:10px">Delivery Details</h2>
                <label>Your Address &nbsp;<span class="mandatory-field">*</span></label><br>
                <textarea class="checkout-item-input" style="width: 40%; min-width: 240px;height: 100px;padding-top:5px" value="" placeholder="Enter your Address" id="address"></textarea>
            </div>
        </div>
        <div class="col-md-9 user-details" style="margin-top: 32px">
            <div class="section">
                <h2>Payment Details</h2>
				<!-- total amount -->
				<div class="col-md-3" style="margin-top: 10px;">
					<div class="cart-items" style="margin-left:0px;color: #975ba5; font-size: 16px;">
						<p style="font-size: 16px;margin-left:0px;width: auto;display: inline-block;">Total</p>
						<div class="cart-item-price">
							Rs. <span id="total-all">{{$orderDetails['orderinfo']->total_with_tax}}</span>
						</div>
					</div>
				</div><br/><br/>
                <div style="margin:16px 8px 0;">
                    <input type="radio" name="delivery" id="delivery" value="online">&nbsp;Online Payment
                    <span style="margin: 0px 15px 0"></span>
                    <input type="radio" name="delivery" id="delivery" value="cod" id="cod" checked> &nbsp;Cash on Delivery<br><br>

                    <label>Donate some money to feed poor children</label><br>
                    <input id="donation" maxlength="10" class="checkout-item-input" style="width: 40%; min-width: 240px; margin-bottom: 10px; border-bottom-style: solid; " placeholder="Enter amount (eg 50, 100, 200)">
                    @if($errors->has('donation'))
                    <ul class="alert alert-danger-custom">
                        <li>
                            Your amount must be numeric. Thanks for donating.
                        </li>
                    </ul>
                    @endif
                    <br>
                    <button type="submit" class="btn checkout-item-button" onclick="PersonalDetails.controllers.saveUserDetails()" style="width: 180px; height: 34px; ">Place the Order Now</button>
                </div>
                </br>
            </div>
        </div>
    </div>
</div>

<!-- CONTACT US
============================== -->


<!-- FOOTER
============================== -->
@include('footer')

</div> <!-- / .wrapper -->


<!-- JavaScript
================================================== -->



<script type="text/javascript" src="{{ URL::asset('js/utils.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/app.js')}}?<?=rand();?>"></script>
<script type="text/javascript" src="{{ URL::asset('js/cart.js')}}?<?=rand();?>"></script>
<script type="text/javascript" src="{{ URL::asset('js/restaurant.js')}}?<?=rand();?>"></script>
<script type="text/javascript" src="{{ URL::asset('js/personal_details.js')}}?<?=rand();?>"></script>
<!-- JS Global -->
<script src="{{ URL::asset('js/plugins/jquery.min.js')}}"></script>

<script src="{{ URL::asset('js/bootstrap/bootstrap.min.js')}}"></script>

<!-- JS Plugins -->
<script src="{{ URL::asset('js/plugins/smoothscroll.js')}}"></script>
<script src="{{ URL::asset('js/plugins/jquery.waypoints.min.js')}}"></script>
<script src="{{ URL::asset('js/plugins/wow.min.js')}}"></script>
<script src="{{ URL::asset('js/plugins/owl.carousel.min.js')}}"></script>
<script src="{{ URL::asset('js/plugins/jquery.peity.min.js')}}"></script>
<script src="{{ URL::asset('js/plugins/lightbox.min.js')}}"></script>

<!-- JS Custom -->
<script src="{{ URL::asset('js/custom.js')}}"></script>
</body>
</html>