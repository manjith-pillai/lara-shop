<table width="100%" cellspacing="0" cellpadding="0" class="" style="margin: 0; padding: 0; width: 100%; font-family: 'Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif"><tbody class=""><tr class="" style="margin: 0; padding: 0"><td class="" style="margin: 0; padding: 0"></td>
        <td bgcolor="#F6F6F6" class="" style="margin: 0 auto!important; padding: 0; background: #f6f6f6; display: block!important; max-width: 800px!important; clear: both!important">
            <br class="" style="margin: 0; padding: 0"><div class="" style="margin: 0 auto 15px; padding: 0; max-width: 600px; display: block; background: #fff">
                <div class="" style="margin: 0;">
                    
                    <table cellspacing="0" cellpadding="0" class="" style="margin: 0; padding: 0; width: 100%"><tbody class=""><tr class="" style="margin: 0; padding: 0"><td class="" style="margin: 0; padding: 0"></td>
                            <td bgcolor="#fff" class="" style="margin: 0 auto!important; background: #fff; display: block!important; max-width: 800px!important; clear: both!important">
                                
                                <table width="100%" cellspacing="0" cellpadding="0" class="" style="margin: 0; padding: 0"><tbody class=""><tr class="" style="margin: 0; padding: 0;background-color: #975ba5"><td width="100" valign="top" align="center" class="" style="margin: 0; padding: 0">
                                            <img style="width:120px;margin-top: 5px;margin-bottom: 5px" src="{{URL::asset('/img/assets/zap_logonew.png')}}"></td>
                                    </tr></tbody></table></td>
                            <td class="" style="margin: 0; padding: 0"></td>
                        </tr></tbody></table></div>

            <div class="" style="margin: 0; padding: 0 15px">
                <table width="100%" cellspacing="0" cellpadding="0" class="" style="margin: 0; padding: 0"><tbody class=""><tr class="" style="margin: 0; padding: 0"><td class="" style="margin: 0; padding: 0">
                            
                            <br class="" style="margin: 0; padding: 0"><p class="" style="margin: 0; padding: 0; margin-bottom: 5px; color: #585858; font-weight: 400; font-size: 13px; line-height: 1.6"><b>Hi
{{ucfirst($order_info['order_info']['order']->customer_name)}},</b></p><p class="" style="margin: 0; padding: 0; margin-bottom: 5px; color: #585858; font-weight: 400; font-size: 13px; line-height: 1.6">Thanks for placing order with Zapdel!. <span style="font-size: 16px">{{$order_info['order_info']['order']->order_history_status}}<span></p>
                        </td>
                    </tr>
                    <tr style="margin: 0; padding: 0">
                        <td style="margin: 0; padding: 0"><i>{{$order_info['order_info']['order']->order_history_comment}}</i></td>
                    </tr>
                    </tbody></table></div>
            <br class="" style="margin: 0; padding: 0">
            <div class="" style="margin: 0; padding: 0 15px">
                <table width="100%" cellspacing="0" cellpadding="0" class="" style="margin: 0; padding: 0">
                <tbody style="font-size:12px">
                <tr>
                    <td style="height:20px">Order No : {{$order_info['order_info']['order']->id}}</td>
                  
                </tr>
                <tr>
                    <td style="height:20px">Delivery Address : {{$order_info['address']}}</td>
                  
                </tr>
                <tr>
                    <td style="height:20px">Mobile no. : {{$order_info['order_info']['order']->cust_contact}}</td>
                    
                </tr>
                <tr>
                    <td style="height:20px">Time of Order : {{date('d F, Y g:i A', strtotime($order_info['order_info']['order']->order_place_time))}}</td>
                    
                </tr>
                 <tr>
                    <td style="height:20px">Payment Type : @if($order_info['order_info']['order']->payment_mode == 'cod')
                        Cash On Delivery
                            @else
                        Online Payment
                        @endif</td>
                    
                </tr>
                 <tr class="" style="margin: 0; padding: 0">
                        <td width="" class="" style="margin: 0; padding: 0;font-weight: bold; font-size: 12px; display: inline-block; vertical-align: top"><p class="" style="margin: 0; padding: 0; color: #1a1a1a; font-weight: bold; font-size: 13px; line-height: 1.6">Grand Total :</p>
                        </td>
                        <td width="" class="" style="margin: 0; padding: 0; font-weight: bold; font-size: 12px; display: inline-block; vertical-align: top"><p class="" style="margin:0 0 0 5px; padding: 0; color: #975ba5; font-weight: bold; font-size: 13px; line-height: 1.6">₹ {{$order_info['order_info']['order']->total_with_tax + $order_info['order_info']['order']->donation}}</p>
                        </td>
                    </tr>
                </tbody>
                </table>
                    </div>
            <br class="" style="margin: 0; padding: 0"><div class="" style="margin: 0;">

                <table width="100%" cellspacing="0" cellpadding="0" class="" style="margin: 0; padding: 0"><thead class="" style="margin: 0; padding: 0; text-align: left; background: #e9e9e9; border-collapse: collapse; border-spacing: 0; border-color: #ccc"><tr class="" style="margin: 0; padding: 0"><th class="" style="margin: 0; padding: 15px; font-size: 14px">Food Item(s)</th>
                <th class="" style="margin: 0; padding: 15px; font-size: 14px">Restaurant(s)</th>
                            <th class="" style="margin: 0; padding: 15px; font-size: 14px;text-align:center">Qty.</th>
                            <th align="right" class="" style="margin: 0; padding: 15px; font-size: 14px">Price</th>
                        </tr></thead>
                        <tbody class="" style="margin: 0; padding: 0">
                         @foreach ($order_info['order_info']['orderDishes'] as $orderDishes)
                        <tr class="" style="margin: 0; padding: 0"><td class="" style="vertical-align: top; margin: 0; padding: 10px; font-weight: bold; font-size: 12px">{{$orderDishes->dish_name}}</td>
                        <td class="" style="margin: 0; padding: 10px; font-weight: bold; font-size: 12px"> {{$orderDishes->name}} 
                        <p style="color: #7b7b7b;font-size: 10px;font-weight: normal;margin-top: 0;">{{$orderDishes->address}}</p>
                        </td>
                                            <td class="" style="margin: 0; padding: 10px; font-weight: bold; font-size: 12px;text-align:center">{{$orderDishes->quantity}} </td>
                                            <td align="right" class="" style="margin: 0; padding: 10px; font-weight: bold; font-size: 12px">
₹&nbsp;{{$orderDishes->price*$orderDishes->quantity}} </td>
                                        </tr><tr class="" width="100%"><td class="">
<div class="" style="min-height: 1px; width: 100%; background: #e9e9e9; clear: both"></div> </td>
                                                <td class=""> <div class="" style="min-height: 1px; width: 100%; background: #e9e9e9; clear: both"></div>
</td>
                                                <td class=""> <div class="" style="min-height: 1px; width: 100%; background: #e9e9e9; clear: both"></div>
</td>
  <td class=""> <div class="" style="min-height: 1px; width: 100%; background: #e9e9e9; clear: both"></div>
</td>
                                            </tr>
                                            @endforeach
                                            </tbody><tfoot class="" style="margin: 0; padding: 0">
                                            <tr class="" style="margin: 0; padding: 0"><th width="80%" class="" style="margin: 0; padding-top: 10px; text-align: right; font-weight: bold; border: 0; font-size: 12px" colspan="3">Net Price</th>
                                        <td width="80%" class="" style="margin: 0; padding-top: 10px; font-weight: bold; border-bottom: 1px solid #e9e9e9; font-size: 12px; text-align: right; border: 0; padding-right: 15px"><span class="">₹&nbsp;{{$order_info['order_info']['total']['total_amount']}}</span></td>
                                    </tr>
                                     <tr class="" style="margin: 0; padding: 0"><th width="80%" class="" style="margin: 0; text-align: right; font-weight: bold; border: 0; font-size: 12px" colspan="3">Service Tax</th>
                                        <td width="80%" class="" style="margin: 0;  font-weight: bold; border-bottom: 1px solid #e9e9e9; font-size: 12px; text-align: right; border: 0; padding-right: 15px"><span class="">₹&nbsp;{{$order_info['order_info']['order']->total_servtax}}</span></td>
                                    </tr>
                                      <tr class="" style="margin: 0; padding: 0"><th width="80%" class="" style="margin: 0;  text-align: right; font-weight: bold; border: 0; font-size: 12px" colspan="3">Vat</th>
                                        <td width="80%" class="" style="margin: 0;  font-weight: bold; border-bottom: 1px solid #e9e9e9; font-size: 12px; text-align: right; border: 0; padding-right: 15px"><span class="">₹&nbsp;{{$order_info['order_info']['order']->total_vat}}</span></td>
                                    </tr>
                                     <tr class="" style="margin: 0; padding: 0"><th width="80%" class="" style="margin: 0;  text-align: right; font-weight: bold; border: 0; font-size: 12px" colspan="3">Donation</th>
                                        <td width="80%" class="" style="margin: 0;  font-weight: bold; border-bottom: 1px solid #e9e9e9; font-size: 12px; text-align: right; border: 0; padding-right: 15px"><span class="">₹&nbsp;{{$order_info['order_info']['order']->donation}}</span></td>
                                    </tr>
                                    <tr class="" style="margin: 0; padding: 0"><th width="80%" class="" style="margin: 0;  text-align: right; font-weight: bold; border: 0; font-size: 12px" colspan="3">Delivery Charges</th>
                                        <td width="80%" class="" style="margin: 0;  font-weight: bold; border-bottom: 1px solid #e9e9e9; font-size: 12px; text-align: right; border: 0; padding-right: 15px"><span class="">₹&nbsp;{{$order_info['order_info']['order']->total_delCharge}}</span></td>
                                    </tr>

                                    <tr class="" style="margin: 0; padding: 0; color: #975ba5;"><th width="80%" class="" style="margin: 0; padding: 10px 0; text-align: right; font-weight: bold; border: 0; font-size: 16px" colspan="3">Grand Total:</th>
                                    <td width="80%" class="" style="margin: 0; padding: 10px 0; font-weight: bold; border-bottom: 1px solid #e9e9e9; font-size: 16px; text-align: right; border: 0; padding-right: 15px"><span class="">₹&nbsp;{{$order_info['order_info']['order']->total_with_tax + $order_info['order_info']['order']->donation}}</span></td>
                                </tr></tfoot></table></div>
            <br class="" style="margin: 0; padding: 0"><div class="" style="margin: 0; padding: 0 15px; padding-top: 15px">

                
                <table width="100%" cellspacing="0" cellpadding="0" class="" style="margin: 0; padding: 0"><tbody class=""><tr class="" style="margin: 0; padding: 0"><td class="" style="margin: 0; padding: 0">
                            
                            
                          <span class="" style="margin: 0; padding: 0; display: block; clear: both"></span>   
                            
                        </td>
                    </tr></tbody></table></div>

            <br class="" style="margin: 0; padding: 0"><div class="" style="margin: 0; padding: 0">

    <div class="" style="margin: 0; padding: 10px; background: #fbfbfb">
    <p style="font-size:16px;margin:0;padding:0"><i>Thank you for using Zapdel.</i></p>
    <p style="font-size:12px;margin:0;padding:0"><i>Contact us on: order@zapdel.com</i></p>

</div>                 
                    <table width="100%" cellspacing="0" cellpadding="0" class="" style="padding: 0 15px;margin: 0;background-color:#000"><tbody class=""><tr class="" style="margin: 0; padding: 0"><td class="" style="margin: 0; padding: 0">

                                
                                <table cellspacing="0" cellpadding="0" align="left" class="" style="margin: 0; padding: 15px 0;"><tbody class=""><tr class="" style="margin: 0; padding: 0"><td class="" style="margin: 0; padding: 0">             
                                            <table cellspacing="0" cellpadding="0" border="0" class="" style="font-family: Arial,Helvetica,sans-serif; font-size: 11px; margin: 0; padding: 0"><tbody class="" style="margin: 0; padding: 0"><tr class="" style="margin: 0; padding: 0">
                                           <td class="" style="margin: 0; padding: 0;color:#fff">Get the App:&nbsp;&nbsp;</td>
                                                        <td class="" style="margin: 0; padding: 0; padding-right: 5px">
                                                            <a rel="noreferrer" target="_blank" class="" style="margin: 0; padding: 0; color: #2ba6cb; display: block" href="https://play.google.com/store/apps/details?id=com.zapdel&hl=en">
                                                                <img alt="" style="width:80px" src="{{url('/img/assets/mailer_google.png')}}"></a>
                                                        </td>
                                                   
</tr></tbody></table></td>
                                    </tr></tbody></table><table cellspacing="0" cellpadding="0" align="right" class="" style="margin: 0; padding: 15px 0; max-width: 142px"><tbody class=""><tr class="" style="margin: 0; padding: 0"><td class="" style="margin: 0; padding: 0">             
                                                                      
 
                                            <table cellspacing="0" cellpadding="0" border="0" class="" style="font-family: Arial,Helvetica,sans-serif; font-size: 11px; margin: 0; padding: 0"><tbody class="" style="margin: 0; padding: 0"><tr class="" style="margin: 0; padding: 0">
                                            <td class="" style="margin: 0; padding: 0; text-align: left;color:#fff">Follow us:&nbsp;&nbsp;</td>
                                                        
                                                        <td class="" style="margin: 0; padding: 0; padding-right: 5px">
                                                            <a rel="noreferrer" target="_blank" class="" style="margin: 0; padding: 0; color: #2ba6cb; display: block" href="https://www.facebook.com/Zapdel.order.delivery">
                                                            <img alt="" style="width: 20px;" src="{{url('/img/assets/mailer_facebook.png')}}">
                                           </a>
                                                        </td>
                                                       
                                                      
                                                        <td class="" style="margin: 0; padding: 0; padding-right: 5px">
                                                            <a rel="noreferrer" target="_blank" class="" style="margin: 0; padding: 0; color: #2ba6cb; display: block" href="http://twitter.com/ZapDel_com">
                                            <img alt="" style="width: 20px;" src="{{url('/img/assets/mailer_twitter.png')}}"></a>
                                                        </td>
                                                   
</tr></tbody></table></td>
                                    </tr></tbody></table></td>
                        </tr></tbody></table></div>
        
                
                <div class="" style="margin: 0; padding: 0 15px; background: #fbfbfb">
                    <br class="" style="margin: 0; padding: 0"><table width="100%" cellspacing="0" cellpadding="0" class="" style="margin: 0; padding: 0"><tbody class=""><tr class="" style="margin: 0; padding: 0"><td class="" style="margin: 0; padding: 0">             
                                <table width="100%" cellspacing="0" cellpadding="0" border="0" class="" style="font-family: Arial,Helvetica,sans-serif; font-size: 11px; color: #9b9b9b; margin: 0; padding: 0"><tbody class="" style="margin: 0; padding: 0"><tr class="" style="margin: 0; padding: 0"><td align="center" class="" style="margin: 0; padding: 0">
                                                &copy; 2016 Zapdel.
All rights reserved.
                                            </td>
                                        </tr></tbody></table></td>
                        </tr></tbody></table><br class="" style="margin: 0; padding: 0"></div>


            </div>
        </td>
        <td class="" style="margin: 0; padding: 0"></td>
    </tr></tbody></table>