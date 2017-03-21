@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;min-height:600px;margin-bottom:30px">
 <div style="margin-top:20px">
 <div class="col-xs-12 col-md-2"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="margin-top:20px">
      <li class="active"><a href="#myaccount" data-toggle="tab"><i class="fa fa-user" aria-hidden="true"></i>
 My Account</a></li>
      <li><a href="#orders" data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i>
 Orders</a></li>
      <li><a href="#address" data-toggle="tab"><i class="fa fa-map-marker" aria-hidden="true"></i> Address</a></li>
    </ul>
</div>

<div class="col-xs-12 col-md-9">
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="myaccount" style="min-height:220px;background:#fff;padding:10px;font-size:16px;border:1px solid #e2e2e2 ;border-radius:5px">
      <?php //dd(Auth::user()) ?>
        <ul>
          <li style="float:left;width:250px">Name: <i>{{ Auth::user()->name }}</i></li>
          <li style="float:left;width:250px">Email: <i>{{ Auth::user()->email }}</i></li>
          <li style="float:left;width:250px">Phone: <i>{{ Auth::user()->phone }}</i> <img src="{{asset('/img/assets/OrderDelivered.png')}}" style="width:25px" alt=""><span style="font-size: 12px;font-style: italic;margin-left: 3px;">Verified</span></li>
          <!-- <li><a href="{{url('/password/email')}}">Change Password?</a></li> -->
        </ul>
      </div>
      <div class="tab-pane" id="orders" style="min-height:220px;background:#fff;padding:10px;font-size:16px;border:1px solid #e2e2e2; border-radius:5px">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
       
 <div class="order-list">
   <ul class="account-list-orders">
    @foreach($myorders as $r)
     <li class="ac-list" style="margin-bottom:10px">
       <div class="col-md-12 no-pad" style="font-size: 13px; background: rgb(251, 251, 251) none repeat scroll 0% 0%; border: 1px solid rgb(226, 226, 226);cursor:pointer">
         <div class="col-md-7">
           <div class="ac-list-title" style="">
             <h5 style="color: #975ba5;
    font-weight: bold;
    margin-bottom: 2px;width:230px;overflow: hidden;
    text-overflow: ellipsis;white-space: nowrap;">{{$r['restaurant_names']}}</h5>
             <div class="ac-list-subtitle" style="font-style: italic; font-size: 11px;">
               #OrderId:{{$r['order_id']}}
             </div>
             <div class="ac-list-date" style="font-size: 12px;
    margin-bottom: 5px;">
               {{date('d F, Y', strtotime($r['order_place_time']))}}
             </div>
           </div>
         </div>
         <div class="col-md-5 text-right">
           <div class="" style="margin-top:20px">Total: Rs.{{$r['total_amt']}}</div>
         </div> 
       </div>
       <div class="clear"></div>
       
       <ul class="ac-list-collapse" style="display:none;padding:0;font-size:13px">
         <li>
            <table class="table">
                    <thead style="background:#e2e2e2">
                        <tr>
                            <th class="text-left">Items</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Total Price</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-bg">
                    @foreach($r['order_dishes'] as $dishes)
                                                <tr>
                            <td class="text-left">{{$dishes->dish_name}}</td>
                            <td class="text-center">
                               {{$dishes->quantity}} x
                            </td>
                            <td class="text-right">Rs. {{$dishes->price}}</td>
                             <td class="text-right">Rs. {{$dishes->price*$dishes->quantity}}</td>
                        </tr>
                        @endforeach
                                                
                                           </tbody>
                </table>
               <div class="col-md-4 col-md-offset-8 no-pad" style="margin-bottom:10px">
                        <div class="">
                            <table style="width: 100%">
                                <tbody>
                                    <tr class="bottom-border">
                                        <td class="col-md-8 col-xs-8 text-right">Subtotal </td>
                                        <td class="col-md-4 col-xs-4 text-right">Rs. <span id="subtotal-all"> {{$r['total_amount']}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">Packaging charges </td>
                                        <td class="col-md-4 col-xs-4 text-right"> Rs. <span id="package-charge">{{$r['total_packcharge']}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">Service charges </td>
                                        <td class="col-md-4 col-xs-4 text-right"> Rs. <span id="service-tax">{{$r['total_sercharge']}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">Service Tax </td>
                                        <td class="col-md-4 col-xs-4 text-right"> Rs. <span id="service-tax">{{$r['total_servtax']}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">VAT  </td>
                                        <td class="col-md-4 col-xs-4 text-right">Rs. <span id="vat">{{$r['total_vat']}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-8 col-xs-8 text-right">Delivery Charges   </td>
                                        <td class="col-md-4 col-xs-4 text-right">Rs. <span id="delivery-charge">{{$r['total_delCharge']}}</span></td>
                                    </tr>
                                    <tr style="color: #975ba5;font-weight: bold; " class="top-border">
                                        <td class="col-md-8 col-xs-8 text-right">Total   </td>
                                        <td class="col-md-4 col-xs-4 text-right">Rs.<span id="total-all">{{$r['total_amt']}}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


         </li>
       </ul>
       
       <li class="clear"></li>
     </li>
    @endforeach

   </ul>
 </div> <!-- end-->

 
</div>


      </div>
      <div class="tab-pane" id="address" style="min-height:220px;background:#fff;padding:10px;font-size:16px;border:1px solid #e2e2e2;">
      @foreach($address as $a)
          
        <div style="margin-top: 10px;" class="ac-addr col-md-4">
          <div style="background: #fbfbfb;border:1px solid #e2e2e2; border-bottom: none;border-top-left-radius: 5px;border-top-right-radius: 5px;
  font-size: 13px;font-weight: bold;padding:5px" class="ac-addr-title">  {{$a->name}}  </div>
          <div style="border: 1px solid rgb(226, 226, 226); padding: 5px;" class="ac-addr-detail"><span style="border: 1px solid #e2e2e2;border-radius: 5px;font-size: 12px;font-style: italic;padding: 3px;" class="address_name">{{$a->address_name}}</span>
          <div style="margin-top: 10px;padding: 5px;"><i aria-hidden="true" class="fa fa-location-arrow"></i> {{$a->address}}</div>
          </div> 
        </div>
        @endforeach
      <div class="clear"></div>
      </div>
    </div>
</div> 

</div>

</div>
<script>
  $(function(){
 $(".ac-list").click(function () {
        $(".ac-list-collapse", this).slideToggle();
    });
 
  });
</script>
@stop