@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div style="display:none" id="booking_id"></div>
<div class="container" style="padding-top: 100px">
    <div class="row">
        <div class="panel panel-default" style="margin-left: 10px; margin-right: 10px">
            <div class="panel-heading"><h3>Order Summary</h3></div>
            <div class="panel-body">
                <input type="hidden" id="number-of-items" value="{{sizeof($orderDetails['dish_details'])}}"/>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left">Restaurant</th>
                            <th class="text-left">Items</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-bg">
                        @for($i=0;$i<sizeof($orderDetails['dish_details']);$i++)
                        <tr class="cart-items" id="cart-item-{{$orderDetails['dish_details'][$i]->rest_dish_id}}">
                            <td class="text-left">{{$orderDetails['dish_details'][$i]->name}}</td>
                            <td class="text-left"><span>{{$orderDetails['dish_details'][$i]->dish_name}}</span></td>
                            <td class="text-center">
                                <span class="checkout-sub-add-r"><img src="{{URL::asset('img/assets/Minus.png')}}" style="width:16px" class="sub" onclick="Cart.controllers.subtract({{$orderDetails['dish_details'][$i]->rest_dish_id}})"></span>
                                <span id="{{$orderDetails['dish_details'][$i]->rest_dish_id}}">{{$orderDetails['dish_details'][$i]->quantity}}</span>
                                <span class="checkout-sub-add-l"><img src="{{URL::asset('img/assets/Plus.png')}}" class="add" style="width:16px" onclick="Cart.controllers.add({{$orderDetails['dish_details'][$i]->rest_dish_id}})"></span>
                            </td>
                            <td class="text-right">Rs. <span id="subtotal-{{$orderDetails['dish_details'][$i]->rest_dish_id}}">{{($orderDetails['dish_details'][$i]->price)*$orderDetails['dish_details'][$i]->quantity}}</span></td>
                        </tr>
                        @endfor
                   </tbody>
                </table>
                <div class="col-md-12 no-pad">
                    <div class="col-md-8 no-pad">
                        <h5>Any Special Instructions</h5>
                        <textarea  class="txtarea" id="specialinstructions" name="specialinstructions" placeholder="Want less oil? more spicy? Allergic to anything? Tell us" maxlength="300" style="width: 450px;height: 80px; margin-bottom: 10px;">{{ old('specialinstructions') }}</textarea>
                    </div>
                    <div class="col-md-4 no-pad">
                        <div class="sub-total-pad">
                            <table style="width: 100%">
                                <tbody>
                                    <tr class="bottom-border">
                                        <td class="col-md-8 col-xs-8 text-right">Subtotal </td>
                                        <td class="col-md-4 col-xs-4 text-right">Rs. <span id="subtotal-all"> {{$orderDetails['orderinfo']->total_amount}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">Packaging charges </td>
                                        <td class="col-md-4 col-xs-4 text-right"> Rs. <span id="package-charge">{{$orderDetails['orderinfo']->total_packcharge}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">Service charges </td>
                                        <td class="col-md-4 col-xs-4 text-right"> Rs. <span id="service-tax">{{$orderDetails['orderinfo']->total_sercharge}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">Service Tax </td>
                                        <td class="col-md-4 col-xs-4 text-right"> Rs. <span id="service-tax">{{$orderDetails['orderinfo']->total_servtax}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">VAT  </td>
                                        <td class="col-md-4 col-xs-4 text-right">Rs. <span id="vat">{{$orderDetails['orderinfo']->total_vat}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">Delivery Charges   </td>
                                        <td class="col-md-4 col-xs-4 text-right"><span id="delivery-charge">FREE</span></td>
                                    </tr>
                                    <tr class="top-border" style="color: #975ba5;font-weight: bold; ">
                                        <td class="col-md-8 col-xs-8 text-right">Total   </td>
                                        <td class="col-md-4 col-xs-4 text-right">Rs.<span id="total-all">{{$orderDetails['orderinfo']->total_with_tax}}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user())

         <div class="panel panel-default" style="margin-left: 10px; margin-right: 10px">
            <div class="panel-heading"><h3>Delivery Address</h3></div>
                 <div class="panel-body">
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-3 col-xs-12">
                            <label class="label-btn"> 
                                <input type="radio" name="delv_address" id="delv_address" value="online"> <span>Address</span>
                                <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit dolor alias rem voluptatem similique quaerat iure. At voluptatum nam sequi.</div>
                            </label>
                         </div>
                         <div class="col-md-2 col-xs-12">
                            <label class="label-btn"> 
                                <input type="radio" name="delv_address" id="delv_address" value="online"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit dolor alias rem voluptatem similique quaerat iure. At voluptatum nam sequi.
                            </label>
                         </div>
                    </div>
                 </div>
                
            </div>
        @else
            <div class="panel panel-default" style="margin-left: 10px; margin-right: 10px">
            <div class="panel-heading"><h3>Delivery Address</h3></div>
            <div class="panel-body">
                <div class="row" style="margin-bottom: 20px">
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-4 col-xs-12">
                            <input type="hidden" id="number-of-items" value="{{sizeof($orderDetails['dish_details'])}}"/>
                            <label>Full Name <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('name')) has-error @endif" placeholder="Enter your Name" id="full_name" maxlength="30" required="true" value="{{ old('name') }}">
                            @if ($errors->has('name')) <p class="error-alert">{{ $errors->first('name') }}</p> @endif
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label>Email ID <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('email')) has-error @endif" placeholder="Enter your Email ID" id="customer_email" name="email" required="true" value="{{ old('email') }}">
                            @if ($errors->has('email')) <p class="error-alert">{{ $errors->first('email') }}</p> @endif
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label>Mobile <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('phone')) has-error @endif"  placeholder="Enter your Mobile No." id="phone" maxlength="10" name="phone" required="true" value="{{ old('phone') }}">
                            <p style="color: #777;font-size: 12px;font-style: italic;margin: 0;"><i>We will verify your mobile no.</i></p>
                            @if ($errors->has('phone')) <p class="error-alert">{{ $errors->first('phone') }}</p> @endif                     
                        </div>
                    </div>
                </div>
                <div class="row user-details">
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-4 col-xs-12">
                            <label>Building/Flat number <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('building')) has-error @endif" placeholder="Building/Flat No." id="building" maxlength="30" required="true" value="{{ old('building') }}">
                            @if ($errors->has('building')) <p class="error-alert">{{ $errors->first('building') }}</p> @endif
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label>Area <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('area')) has-error @endif" value="{{ old('area') }}" placeholder="Area" id="area" maxlength="150" required="true">
                            @if ($errors->has('area')) <p class="error-alert">{{ $errors->first('area') }}</p> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
<!--         <div class="panel panel-default" style="margin-left: 10px; margin-right: 10px">
            <div class="panel-heading"><h3>Delivery Address</h3></div>
            <div class="panel-body">
                <div class="row" style="margin-bottom: 20px">
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-4 col-xs-12">
                            <input type="hidden" id="number-of-items" value="{{sizeof($orderDetails['dish_details'])}}"/>
                            <label>Full Name <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('name')) has-error @endif" placeholder="Enter your Name" id="full_name" maxlength="30" required="true" value="{{ old('name') }}">
                            @if ($errors->has('name')) <p class="error-alert">{{ $errors->first('name') }}</p> @endif
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label>Email ID <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('email')) has-error @endif" placeholder="Enter your Email ID" id="customer_email" name="email" required="true" value="{{ old('email') }}">
                            @if ($errors->has('email')) <p class="error-alert">{{ $errors->first('email') }}</p> @endif
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label>Mobile <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('phone')) has-error @endif"  placeholder="Enter your Mobile No." id="phone" maxlength="10" name="phone" required="true" value="{{ old('phone') }}">
                            <p style="margin:0"><i>(We will verify your mobile no.)</i></p>
                            @if ($errors->has('phone')) <p class="error-alert">{{ $errors->first('phone') }}</p> @endif                     
                        </div>
                    </div>
                </div>
                <div class="row user-details">
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-4 col-xs-12">
                            <label>Building/Flat number <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('building')) has-error @endif" placeholder="Building/Flat No." id="building" maxlength="30" required="true" value="{{ old('building') }}">
                            @if ($errors->has('building')) <p class="error-alert">{{ $errors->first('building') }}</p> @endif
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label>Area <span class="mandatory-field">*</span></label>
                            <input class="form-control checkout-item-input @if ($errors->has('area')) has-error @endif" value="{{ old('area') }}" placeholder="Area" id="area" maxlength="150" required="true">
                            @if ($errors->has('area')) <p class="error-alert">{{ $errors->first('area') }}</p> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

<div class="panel panel-default" style="margin-left: 10px; margin-right: 10px">
            <div class="panel-heading"><h3>Donation (Optional)</h3></div>
            <div class="panel-body">
                <div class="col-md-4 col-xs-12">
                            <label>Donate some money to feed poor children</label>
                            <input class="form-control checkout-item-input @if ($errors->has('donation')) has-error @endif" placeholder="Enter amount (eg 50, 100, 200)" id="donation" maxlength="6" name="donation" value="{{ old('donation') }}">
                            @if ($errors->has('donation')) <p class="error-alert">{{ $errors->first('donation') }}</p> @endif
                        </div>
                <div class="col-md-12" style="">
                <div class="donation_info">
                 <p> <i>A part of our earning from each delivery goes to feed someone who needs it through Akshaya Patra, a well known NGO, working towards providing food to children in primary schools.</i> </p>     
                </div>
                
                </div>   
            </div>
        </div>

    <div class="panel panel-default" style="margin-left: 10px; margin-right: 10px">
            <div class="panel-heading"><h3>Payment Method</h3></div>
            <div class="panel-body">
                <div class="row">
                    <span id="total-all"></span>
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-2 col-xs-12">
                            <label class="label-btn"> Online Payment
                                <input type="radio" name="delivery" id="delivery" value="online" @if(Input::old('delivery')== "online") {{'checked="checked"'}} @endif checked>
                            </label>
                         </div>
                        <div class="col-md-2 col-xs-12">
                            <label class="label-btn"> Cash on Delivery 
                                <input type="radio" name="delivery" value="cod" id="cod" @if(Input::old('delivery')== "cod") {{'checked="checked"'}} @endif>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    <div class="col-md-12 text-center">
        <button type="submit" class="btn checkout-item-button" onclick="PersonalDetails.controllers.saveUserDetails()" style="width: 180px; height: 34px;margin-bottom: 20px; ">Place the Order Now</button>
    </div>
    <input type="text" value="{{ csrf_token() }}" id="csrf_token" style="display: none">
</div>
</div>
<!-- Verify Modal -->
<div class="modal fade" id="verify_modal" tabindex="-1" role="dialog" aria-labelledby="verification">
<div role="document" class="modal-dialog modal-size">
    <div class="modal-content">
    <!-- <div style="background: red none repeat scroll 0 0;height: 100%;position: absolute;width: 100%;z-index: 9999;">
        <div>hfhfgh</div>
    </div> -->
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="verification" class="modal-title">Mobile Verification</h4>
      </div>
     
      <div class="modal-body" style="padding-bottom: 0px;">
      
      <div class="row">
          <div class="col-md-3 text-center">
              <img alt="" src="{{url('/img/assets/Mobile.png')}}" style="width:75px">
          </div>
          <div class="col-md-9">
              <label for="verify"><h5>We have sent an OTP on your mobile no.</h5></label>
            <div class="col-md-8" style="padding:0">
                <input maxlength="6" class="form-control" placeholder="Enter the verification code" id="otp_code" name="verification">
            <div id="otp_error"></div>
            <div id="msg" style="display:none">We have sent a new OTP</div>
            <div id="timer"></div>
            </div>
            <div class="col-md-4" style="padding-right: 0;padding-top: 8px;display:none" id="resend"><a style="text-decoration:none" onclick="" href="javascript:void(0);" id="resend_link">Resend OTP</a></div>
          </div>
      </div>
      
      </div>
      <div style="text-align: center;" class="modal-footer">
        <button class="btn btn-default" onclick="PersonalDetails.controllers.verifyOTP()" id="mobile_verify" type="submit">Verify</button>
        <div id="ab"></div>
      </div>
    </div>
  </div>
</div>
<!--  end -->

<script type="text/javascript" src="{{ URL::asset('js/utils.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/cart.js')}}?<?=rand();?>"></script>
<script type="text/javascript" src="{{ URL::asset('js/restaurant.js')}}?<?=rand();?>"></script>
<script type="text/javascript" src="{{ URL::asset('js/personal_details.js')}}?<?=rand();?>"></script>

@if(Session::has('mobile_verification_status'))
<script>
    $(function(){
       $('#verify_modal').modal('show');
    });
</script>
@endif

<script>
    $(function(){
        var count=45;
        var counter=setInterval(timer, 1000); 
        function timer()
        {
            count=count-1;
            if (count <= 0)
            {
                clearInterval(counter);
                $('#resend').show();
                $('#timer').hide();
            return;
            }
        }
        $('#resend_link').click(function(){
            var name=$$('full_name').value;
            var email=$$('customer_email').value;
            var phone=$$('phone').value;
            var area=$$('area').value;
            var building=$$('building').value;

            var postData = {
                'email' : $$('customer_email').value,
                'phone': $$('phone').value,
                'specialinstructions': $$('specialinstructions').value,
                'area': $$('area').value,
                'building':$$('building').value,
                'address' : $$('building').value+' '+$$('area').value,
                'name':$$('full_name').value,
                'donation':$$('donation').value,
                'delivery':$('input[name=delivery]:checked').val(),
                'amount':$$('total-all').innerHTML
            };
            $.ajax({
                url: '{{ url("/make_payment/")}}',
                method:'post',
                data:postData,
                beforeSend: function() {
       
                    $('#resend').fadeOut("slow").hide();
                    $('#msg').show();
                },
                success:function(data){
                    $('#resend').hide();
                    var count=45;
                    var counter=setInterval(timer, 1000); 
                    function timer()
                    {
                        count=count-1;
                        if (count <= 0)
                        {
                        clearInterval(counter);
                        $('#resend').show();
                        return;
                        }
  
                    }    
   
                },
                complete:function() {
                   //$('#resend').show();
                }

            });
           
        });
    });
</script>
@stop