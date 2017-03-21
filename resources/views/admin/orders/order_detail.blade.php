@extends('admin.dashboard.layouts.home')
@section('admin_content')
<div class="container-fluid">
@if (count($order_detail) > 0)
<div class="panel panel-default">

	<div class="panel-heading">
		<a href="{{ url('admin/order/order-list') }}" style="color:#337ab7">Orders </a> >> Order #{{ $order_detail['order']->id }} Detail
	</div>

<div class="row">
@if(Session::has('assignment_message'))
<div class="col-md-12">    
    {{ Session::get('assignment_message') }}
    
</div>
@endif
<div class="col-md-6">
	<div class="table-responsive">
  <table class="table table-striped">
  <h4>Order Details</h4>
    <tbody>
    	<tr>
    		<td class="col-md-3 col-xs-2">Order ID:</td>
    		<td>#{{ $order_detail['order']->id}}</td>
    	</tr>
      <tr>
        <td class="col-md-3 col-xs-2">Order place time:</td>
        <td>{{ $order_detail['order']->order_place_time_format}}</td>
      </tr>
      <tr>
        <td class="col-md-3 col-xs-2">Payment Mode:</td>
        <td>{{ $order_detail['order']->payment_mode}}</td>
      </tr>
      <tr>
        <td class="col-md-3 col-xs-2">Payment Status:</td>
        <td>{{ $order_detail['order']->payment_status}}</td>
      </tr>
    	<tr>
    		<td class="col-md-3 col-xs-2">Total:</td>
    		<td>Rs. {{ ($order_detail['order']->total_with_tax + $order_detail['order']->donation) }}</td>
    	</tr>
    	
    	<tr>
    		<td class="col-md-3 col-xs-2">Order Status:</td>
    		<td>{{ $order_detail['order']->order_status}}</td>
    	</tr>
		<tr>
    		<td class="col-md-3 col-xs-2">Order Source:</td>
    		<td>{{ $order_detail['order']->order_source }}</td>
    	</tr>
      <tr>
        <td class="col-md-3 col-xs-2">Donation:</td>
        <td>Rs. {{ $order_detail['order']->donation }}</td>
      </tr>
    </tbody>
  </table>
  </div> <!-- End of table -->
  </div>

<div class="col-md-6">
  <div class="table-responsive">
  <table class="table table-striped">
  <h4>Customer Details</h4>
    <tbody>
      <tr>
        <td class="col-md-3 col-xs-2">Name:</td>
        <td>{{ $order_detail['order']->customer_name}}</td>
      </tr>
        <tr>
        <td class="col-md-3 col-xs-2">Email:</td>
        <td>{{ $order_detail['order']->customer_email}}</td>
      </tr>
      <tr>
        <td class="col-md-3 col-xs-2">Mobile:</td>
        <td>{{ $order_detail['order']->cust_contact}}</td>
      </tr>
      <tr>
        <td class="col-md-3 col-xs-2">Address:</td>
        <td>{{ $order_detail['order']->address}}</td>
      </tr>
      <tr>
        <td class="col-md-3 col-xs-2">Special Instructions:</td>
        <td>{{ $order_detail['order']->spl_instruct}}</td>
      </tr>
    </tbody>
  </table>
  </div> <!-- End of table -->
  </div>

</div> <!-- End of row -->
</div>


<div class="panel panel-default">
	<div class="panel-heading">
		Order Items
	</div>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Restaurant</th>
					<th>Food Items</th>
					<th>Item Category</th>
					<th>Quantity</th>
					<th>Unit Price</th>
					<th style="text-align:right">Total</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($order_detail['orderDishes'] as $order)
				<tr>
					<td>{{ $order->resto_name }}</td>
					<td>{{ $order->dish_name }}</td>
					<td>{{ $order->cuisine_name }}</td>
					<td>{{ $order->quantity }}</td>
					<td>{{ $order->unit_price }}</td>
					<td align="right">{{ $order->price }}</td>
				</tr>
				 @endforeach
				        <tr>
                    <td colspan="5" align="right">Sub-Total:</td>
                    <td align="right">Rs. {{ $order_detail['order']->total_amount }}</td>
                 </tr>
                <tr>
                    <td colspan="5" align="right">Packaging charges:</td>
                    <td align="right">{{ $order_detail['order']->total_packcharge }}</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">Service Charges:</td>
                    <td align="right">{{ $order_detail['order']->total_sercharge }}</td>
                </tr>
                 <tr>
                    <td colspan="5" align="right">Service Tax:</td>
                    <td align="right">{{ $order_detail['order']->total_servtax }}</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">Vat:</td>
                    <td align="right">{{ $order_detail['order']->total_vat }}</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">Delivery Charges:</td>
                    <td align="right">{{ $order_detail['order']->total_delCharge }}</td>
                </tr>
                <tr>
                   	<td colspan="5" align="right">Total:</td>
                    <td align="right">Rs. {{ $order_detail['order']->total_with_tax}}</td>
                </tr>
                @if($order_detail['order']->donation > 0)
                <tr>
                    <td colspan="5" align="right">Total with Donation:</td>
                    <td align="right">{{ $order_detail['order']->total_with_tax + $order_detail['order']->donation}}</td>
                </tr>
                @endif
			</tbody>
		</table>
	</div>
</div>

<div class="row" style="margin-top: 20px">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Update Status</div>
			    <div class="panel-body">
                    <form action="{{ url('order_history_update/'.$order_detail['order']->id) }}" method="POST">
                        <div class="form-group">
                          <label for="email">Order Status:</label>
                          <select name="statlist" class="form-control">
                              @foreach ($order_status_list as $order_status_row)
                                   <option value="{{ $order_status_row->order_status_id}}" {{ ($order_status_row->order_status_id == $order_detail['order']->order_status_id) ? 'selected' : '' }}>{{ $order_status_row->name}}</option>
                              @endforeach
                          </select>
                        </div>
                        <!--Select delivery boy-->
                        <div class="form-group">
                          <label for="email">Select Delivery Boy:</label>
                          <select name="delivery_boy_id" class="form-control">
                              <option value="">Select</option>    
                              @foreach ($delivery_boy_list as $delivery_boy_row)
                                   <option value="{{ $delivery_boy_row->id}}" {{ ($delivery_boy_row->id == $assigned_delivery_boy_id) ? 'selected' : '' }}>{{ $delivery_boy_row->name}}</option>
                              @endforeach
                          </select>
                        </div>
                        <!--end of select delivery boy-->
                        <div class="form-group">
                            <label for="email">Notify Customer:</label>
                            <input type="checkbox" name="notify" value="1" checked="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Comment:</label>
                            <textarea name="statComment" id="" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-default">Update</button>
                        </div>
                    </form>
                </div>
		</div>
	</div>
	<div class="col-md-8">
  <div class="panel panel-default">
                <div class="panel-heading"><h4>Delivery Boy Information</h4></div>
                
          <div class="table-responsive">
                   
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Delivery Boy Id : Name</th>
                          <th>Assign time</th>
                          <th>Status</th>
                          <th>Accept / Reject time</th>
                          <th>Delivered Time</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($delivery_boys_info as $delivery_boy_information)
                      <?php 
                            $time_of_assignment = date('d-m-Y H:i:s A', strtotime($delivery_boy_information->time_of_assignment));
                          if (!empty($delivery_boy_information->delivery_completed_at) && $delivery_boy_information->delivery_completed_at != '0000-00-00 00:00:00') {
                              $delivery_completed_at = date('d-m-Y H:i:s A', strtotime($delivery_boy_information->delivery_completed_at));
                          } else {
                            $delivery_completed_at = '';
                          }
                            
                            if (!empty($delivery_boy_information->time_of_acceptance) && $delivery_boy_information->time_of_acceptance == '0000-00-00 00:00:00') {
                              $time_of_acceptance = date('d-m-Y H:i:s A', strtotime($delivery_boy_information->time_of_acceptance));
                              } else {
                              $time_of_acceptance = $delivery_boy_information->time_of_acceptance;
                                  }
                                    if ($delivery_boy_information->is_accept == 2) {
                                         $status = 'Rejected';
                                       } elseif ($delivery_boy_information->is_accept == 1) {
                                              $status = 'Accepted';
                                            } elseif($delivery_boy_information->is_accept == 3) {
                                                 $status = ' No Response';
                                                  } elseif ($delivery_boy_information->is_accept == 4) {
                                                      $status = 'Completed';
                                                    }  else {
                                                        $status = 'Pending';
                                                        }
                        ?> 
                        <tr>
                           
                           <td>{{ $delivery_boy_information->id }} : {{ $delivery_boy_information->name }}</td>
                           <td>{{ $time_of_assignment }}</td>
                           <td>{{ $status }}</td>
                           <td>{{ $time_of_acceptance }}</td>
                           <td>{{$delivery_completed_at}}</td>
                        </tr>
                        @endforeach
                       </tbody>
                    </table>
                    <!--<div class="pagination"><div class="results">Showing 1 to 4 of 4 (1 Pages)</div></div>-->
                     </div>
                </div>
    
		<div class="panel panel-default">
                <div class="panel-heading"><h4>Order History</h4></div>
                
					<div class="table-responsive">
                   
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Date Added</th>
                          <th>Comment</th>
                          <th>Status</th>
                          <th>Customer Notified</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($order_detail['orderHistory'] as $order_history)
                        <tr>
                           <td>{{ $order_history->order_history_date_added }}</td>
                           <td>{{ $order_history->comment }}</td>
                           <td>{{ $order_history->order_status }}</td>
                           <td>{{ !empty($order_history->notify) ? 'Yes' : 'No' }}</td>
                         </tr>
                        @endforeach
                       </tbody>
                    </table>
                    <!--<div class="pagination"><div class="results">Showing 1 to 4 of 4 (1 Pages)</div></div>-->
                     </div>
                </div>
           
	</div>
</div>
<script>
    
    function checkNewOrderAssignmentStatus() {
        var dt = new Date();
        var c_time = dt.getFullYear() + "-" + (dt.getMonth()+1) + "-"+ dt.getDate() + " "+ dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
        $.ajax({
            url: "{{URL('admin/system/checkNewOrderAssignmentStatus/')}}",
            type: "POST",
            data: { order_id : '{{ $order_detail['order']->id}}'},
            dataType: 'json',  
            success: function (result) {
                if(result.found == 1) {
                    setTimeout(function(){ window.location.reload(1); }, 10000);
                    
                }
                console.log('result.found :'+result.found);
            }
        });
        
     }
     
       setInterval(function(){checkNewOrderAssignmentStatus();}, 30000);
    </script>


@endif
</div>

@stop
