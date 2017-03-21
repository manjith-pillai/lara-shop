@extends('admin.dashboard.layouts.home')
@section('admin_content')
<div class="container-fluid">
@if (count($orders) > 0)
<div class="panel panel-default">
  <div class="panel-heading">
    <span style="font-size:24px">Current Orders</span>
  </div>
  <div class="table-responsive">
  <table class="table table-striped">
    <thead>
    	<tr>
    		<th>Order ID</th>
            <th>Source</th>
    		<th>Customer</th>
                <th>Customer Email</th>
    		<th>City</th>
            
    		<th>Shipping Address</th>
    		<th>Payment Mode</th>
    		<th>Payment Status</th>
    		<th>Order Status</th>
    		<th>Place time</th>
    		<th>Total</th>
    		<th>Action</th>
    	</tr>
    </thead>
    <tbody>
    	 @foreach ($orders['order'] as $order)
           <?php $tr_bkc = '';
            if ($order->is_viewed == 0)
                $tr_bkc = "style=background:#f4dd70;";
            elseif ($order->order_status == "Complete")
               $tr_bkc = "style=background:#A3E066;";
            elseif ($order->order_status == "Processing")
               $tr_bkc = "style=background:orange;";
            elseif ($order->order_status == "Pending")
               $tr_bkc = "style=background:#E08888;";
            else
               $tr_bkc = "";
           ?>
         <tr {{ $tr_bkc}}>
    		<td>{{ $order->id }}</td>
            <td>
                @if($order->order_source == "android_app")
                    <img src="{{asset('img/assets/androidiconsmall.png')}}" style="height:22px" alt="android" title="Android App"> 
                    @else
                   <img src="{{asset('img/assets/wwwicon.png')}}" style="height:20px" alt="www" title="Website"> 
                @endif
            </td>
    		<td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_email }}</td>
    		<td>{{ $order->city_name }}</td>
    		<td>{{ $order->address }}</td>
    		<td>{{ $order->payment_mode }}</td>
    		<td>{{ $order->payment_status }}</td>
    		<td>{{ $order->order_status }}</td>
    		<td>{{ $order->order_place_time_format }}</td>
    		<td>{{ $order->total_with_tax + $order->donation}}</td>
    		<td><a style="color:#337ab7" href ="{{ url('admin/order/order-detail/'.$order->id) }}">View</a>
    		</td>
    	</tr>
    	@endforeach
    	
    </tbody>
  </table>
  {!! $orders['order']->render() !!}
  </div>
</div>
 @endif
 <div id="sound"></div>
 <script>
    
    function checkNewOrder() {
        var dt = new Date();
        var c_time = dt.getFullYear() + "-" + (dt.getMonth()+1) + "-"+ dt.getDate() + " "+ dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
        $.ajax({
            url: "{{URL('system/admin/checkLatestOrder/')}}",
            type: "POST",
            data: { current_time : c_time},
            dataType: 'json',  
            success: function (result) {
                if(result.found == 1) {
                    var title='#'+result.orderInfo.id+' New Order Place';
                    var desc='Zapdel';
                    var url='{{URL('admin/order/order-detail')}}/'+result.orderInfo.id;
                    notifyBrowser(title,desc,url);
                    setTimeout(function(){ window.location.reload(1); }, 10000);
                    
                }
                console.log('result.found :'+result.found);
            }
        });
        
     }
     
     function notifyBrowser(title,desc,url) {
         if (!Notification) {
             console.log('Desktop notifications not available in your browser..'); 
             return;
        }
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        } else {
            var notification = new Notification(title, {
                               icon:'{{URL::asset('/img/assets/zapdel_white.png')}}',
                               body: desc,
             });
             
             notification.onshow = function() {
                 playSound('{{URL::asset('/img/assets/system-notification')}}');
             };
             // Remove the notification from Notification Center when clicked.
             notification.onclick = function () {
                 window.open(url);
             };
             // Callback function when the notification is closed.
             notification.onclose = function () {
                 console.log('Notification closed');
                 //notification.sound;
             };
         }
       }
       
       function playSound(filename){
           document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
       }
       setInterval(function(){checkNewOrder();}, 30000);
    </script>
</div>

@stop