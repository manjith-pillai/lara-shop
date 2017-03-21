@extends('layoutn.main')
@section('checkout_redirect')
<script type="text/javascript">
        window.onload = function() {
            setTimeout(submitForm, 2000);
        }
        function submitForm() {
            document.forms[0].submit();
        }
    </script>

@stop
@section('content')
<div class="container" style="padding-top: 70px;min-height:500px">

		  
		 <div class="col-md-12" style="margin-top:80px">
		 	<div class="col-lg-4 col-lg-offset-5">
		 		<div style="width: 40px; float: left;">
		 			<div class="loading"></div>
		 			
		 		</div>
		 		<div style="color: #975ba5;font-size: 16px;margin-top: 5px;">REDIRECTING TO PAYU</div>
		 	</div>
		 	<form method="post" action="{{$payu_payment_url}}">
				<input type="hidden" name="key" value="{{$merchant_key}}" >
				<input type="hidden" name="txnid" value="{{$order_id}}" >
				<input type="hidden" name="amount" value="{{$amount}}" >
				<input type="hidden" name="productinfo" value="{{$product_info}}" >
				<input type="hidden" name="firstname" value="{{$first_name}}" >
				<input type="hidden" name="email" value="{{$email}}" >
				<input type="hidden" name="phone" value="{{$phone}}" >
				<input type="hidden" name="surl" value="{{$surl}}" >
				<input type="hidden" name="furl" value="{{$furl}}" >
				<input type="hidden" name="hash" value="{{$checksum}}" >
			</form>
		 </div>
			
</div>
@stop
