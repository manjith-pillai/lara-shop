<?php
/**
 * Created by PhpStorm.
 * User: Rishabh
 * Date: 10/12/15
 * Time: 1:18 AM
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
    <link href="{{ URL::asset('css/styles.css') }}" rel="stylesheet">


    <link type="text/css" rel="stylesheet">

</head>

<body data-spy="scroll" data-target=".navbar" data-offset="71">
<div style="display:none" id="booking_id"></div>

<!-- SIDEBAR
============================== -->
<div class="sidebar">
    <div class="sidebar__wrapper">

        <div class="sidebar__close js-toggle-sidebar visible-xs">
            <span class="oi oi-x"></span>
        </div>

        <div class="sidebar__form form_alt">
            <form>
                <div class="form-group">
                    <label class="sr-only" for="login__username">Your Location</label>
                    <input type="text" class="form-control" id="login__username" placeholder="Username">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="login__password">Restaurant Or Cuisine</label>
                    <input type="password" class="form-control" id="login__password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-block btn-primary">Sign In</button>
            </form>
        </div>

        <h4 class="sidebar__heading">
            Layouts
        </h4>
        <ul class="sidebar__menu">
            <li><a href="index_agency.html">Agency</a></li>
            <li><a href="index_app.html">App</a></li>
            <li><a href="index_sign-up.html">Sign Up</a></li>
        </ul>

        <h4 class="sidebar__heading">
            Bonus pages
        </h4>
        <ul class="sidebar__menu">
            <li><a href="sign-in.html">Sign In</a></li>
            <li><a href="sign-up.html">Sign Up</a></li>
            <li><a href="coming-soon.html">Coming Soon</a></li>
        </ul>

        <div class="sidebar__logo">
            Zapdel
        </div>

    </div>
</div>


<!-- WRAPPER
============================== -->
<div class="wrapper">

    <!-- NAVBAR
    ============================== -->
    <nav class="navbar navbar-default navbar-fixed-top js-navbar-top js-toggleClass">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#home">Zapdel</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right ">
                    <li class="active"><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#team">Pre-order</a></li>
                    <li><a href="#skills">Login</a></li>
                    <li><a href="#contact">Sign Up</a></li>
                    <li class="hidden-xs">
                        <p class="navbar-text navbar__separator"></p>
                    </li>
                    <li>
                        <a href="#" class="js-toggle-sidebar">
                            <span class="oi oi-menu hidden-xs"></span>
                            <span class="visible-xs">Bonus pages</span>
                        </a>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>


    <div class="container">
        <div class="row" style="margin-top: 110px">
            <div class="col-xs-pull-12">

                <div class="col-xs-3 checkout-next-step" style="color: #45BA7E">
                    <img src="{{URL::asset('img/assets/Done.png')}}" style="width: 30px">
                    &nbsp; &nbsp;Confirm Checkout
                </div>

                <div class="col-xs-3 checkout-next-step" style="color: #45BA7E">
                    <img src="{{URL::asset('img/assets/Done.png')}}" style="width: 30px">
                    &nbsp; &nbsp;Your Details
                    <!-- <svg>
                         <circle cx="25" cy="25" r="20" stroke="#975ba5" fill="white" stroke-width="2"/>
                     </svg>-->
                </div>

                <div class="col-xs-4 checkout-selected" >
                    <img src="{{URL::asset('img/assets/Done.png')}}" style="width: 40px">
                    &nbsp; &nbsp; Verify and Place
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row" style="margin-left: 2%">
            <div class="col-md-9 user-details" style="margin-top: 48px">

                    <div class="col-md-2">
                        <img src="{{URL::asset('img/assets/Mobile.png')}}">
                    </div>
                    <div class="col-md-9">
                        <p style="display: block; font-size: 30px;margin-bottom: 0px;">Verify your mobile Number</p>
                        <p style="display: block; font-size: 14px;margin-bottom: -2px; color: #909090"> We have sent a verification code on your mobile +91-9460862343</p>
                        <p style="display: block; font-size: 14px;margin-bottom: 32px; color: #909090"> Please enter it below to place order.</p>
                        <input class="checkout-item-input" style="width: 48%; min-width: 240px" placeholder="Enter the verification code">
                        <button type="submit" class="btn checkout-item-button" style="width: 150px; height: 34px;top:-1px ">Verify and Pay</button>
                    </div>
            </div>
            <div class="col-md-3" style="margin-top: 24px; padding: 8px; background-color: #F9F9F9; color: #975ba5">
                <div style="display: inline-block"><img src="http://localhost:8000/img/assets/Cuisine.png" width="45px"></div>
                <div style="display: inline-block;font-size: 13px; margin-bottom: 32px"> &nbsp;&nbsp;Your Tray ( 0 Items)</div>
                <div style="max-height: 236px; overflow-x: hidden">
                    @for($i=0;$i<6;$i++)
                    <div class="cart-items">
                        <div class="col-md-1" style="margin:0px 15px 0px -15px"><img src="http://localhost:8000/img/assets/Veg.png" style="width: 16px; margin-right: 8px"></div>
                        <p>Paneer Tika Medium Spicy Special 6pc.</p>
                        <div class="cart-item-price">
                            Rs. 160
                        </div>

                        <div class="col-md-9" style="margin-left: 5%">
                            <img src="http://localhost:8000/img/assets/Minus.png" style="width: 16px; margin-right: 4px; cursor: pointer">
                            1
                            <img src="http://localhost:8000/img/assets/Plus.png" style="width: 16px; margin-left: 4px;cursor: pointer">
                            <span style="font-size: 10px;color: #A79D9D">x Rs.160</span>
                        </div>
                    </div><br>
                    <div class="checkout-item-heading" style="border-top: 1px solid #C7C4C4;position: relative; top: 8px; margin: 0 15px 0 15px"></div>
                    @endfor
                </div>
                <div class="col-md-12" style="margin-top: 16px;">
                    <div class="cart-items">
                        <p style="color: #909090">Sub Total </p>
                        <div class="cart-item-price">
                            Rs. 160
                        </div>
                    </div>
                    <div class="cart-items" style="color: #975ba5; font-size: 16px; padding:0px 0 8px 0; ">
                        <p style="font-size: 16px;">Total </p>
                        <div class="cart-item-price">
                            Rs. 300
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTACT US
    ============================== -->


    <!-- FOOTER
    ============================== -->

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2015 Zapdel.com. All Rights Reserved.
                </div>
                <div class="col-sm-6">
                    <div class="footer__social">
                        <a href="#" class="footer-social__link footer-social__link_twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="#" class="footer-social__link footer-social__link_facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" class="footer-social__link footer-social__link_google-plus">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div> <!-- / .wrapper -->


<!-- JavaScript
================================================== -->



<script type="text/javascript" src="{{ URL::asset('js/utils.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/app.js')}}?<?=rand();?>"></script>

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