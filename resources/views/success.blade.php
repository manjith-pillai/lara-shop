@extends('layoutn.main')
@section('order_track_success')
<!-- Google Code for Order Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 919390217;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "zsN_COG8-GUQiZCztgM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript"  src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt=""  src="//www.googleadservices.com/pagead/conversion/919390217/?label=zsN_COG8-GUQiZCztgM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
@stop
@section('content')
@include('sign-in-modal')
<div style="display:none" id="booking_id"></div>

    <div class="container" style="padding-top: 100px">
        <div class="row">
            <div class="col-md-1 no-pad text-center">
                @if($orderDetails['status']=='success')
                    <img src="{{URL::asset('img/assets/DoneBig.png')}}">
                    @else
                    <img src="{{URL::asset('img/assets/Cancelled.png')}}">
                @endif
            </div>
            <div class="col-md-10">
            @if($orderDetails['status']=='success')
                <h2 style="line-height: 1.5">Congratulations! your order has been Successfully placed.</h2>
            @else
                <h2 style="line-height: 1.5">Sorry ! Could not place your order</h2>
            @endif
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-6">
             @if($orderDetails['status']=='success')
                <h5><img style="height:22px;margin-right:17px" src="{{asset('img/assets/Location.png')}}">Order will be deliverd at: {{$orderDetails['address']}}</h5>
                <h5><img src="{{asset('img/assets/money.png')}}" style="height:15px;margin-right:17px">Total Amount: Rs. {{$orderDetails['total_with_tax']}}</h5>
                @if($orderDetails['dona'])
                    <h5><img style="height:22px;margin-right:10px" src="{{asset('/img/assets/donationicon.png')}}">Thanks for donation of Rs. {{$orderDetails['dona']}}</h5>
                @endif
            @endif
            </div>

        </div>
        <div class="row" style="margin-bottom: 200px">
        <div class="col-md-12" style="margin-top: 10px">
            <button type="submit" class="btn checkout-item-button" style="width: 200px; height: 34px;" onclick="window.location.href='{{url('/')}}'">Continue with Website</button>
            <div style="margin-top: 50px;">
                <p>If you don't receive a confirmation email on your registered email address within 10 minutes, please mail us having Subject as 'NO CONFIRMATION' at <a href="mailto:order@zapdel.com?subject=NO CONFIRMATION">order@zapdel.com</a></p>
                <p>Please share your experience about Zapdel Ordering &amp; Delivery on <a href="mailto:experience@zapdel.com?subject=Feedback on Zapdel Ordering &amp; Delivery" "="">experience@zapdel.com</a></p>
            </div>
        </div>
    </div>
</div>

@stop