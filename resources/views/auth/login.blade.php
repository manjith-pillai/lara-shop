@extends('app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-5" style="float:none;margin:0 auto">
			<div class="panel panel-default">
				<div class="panel-heading">Login</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
						<div class="col-md-6">
								<input type="email"  placeholder="E-mail address" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6">
								<input type="password" placeholder="Password" class="form-control" name="password">
							</div>
						</div>

						<!-- <div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> Remember Me
									</label>
								</div>
							</div>
						</div> -->

						<div class="form-group">
							<div class="col-md-5" style="float:none;margin:0 auto">
								<button type="submit" class="btn btn-primary">Login</button>

								<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
 <div id="footer">
      <footer>
      		<div class="container-fluid">
					<div class="row">
						
						<div class="col-md-4 text-left">
              					
	           
            			</div>
						

						<div class="col-md-4 text-center">
							<div align="" class="footer-head">
				                <a href="http://twitter.com/Zap_Delivery" class="footer-social__link footer-social__link_twitter" style="background-image: url({{URL::asset('/img/assets/Twitter.png')}}); background-size: 100%; background-color: #eee">
				                  <i class="fa fa-twitter"></i>
				                </a>
				                <a href="http://www.facebook.com/boibanit" class="footer-social__link footer-social__link_facebook" style="background-image: url({{URL::asset('/img/assets/Facebook.png')}}) ; background-size: 100%; background-color: #eee" >
				                  <i class="fa fa-facebook"></i>
				                </a>
				                 <a href="https://plus.google.com/116621667263669264867" class="footer-social__link footer-social__link_facebook" style="background-image: url({{URL::asset('/img/assets/google_plus.png')}}) ; background-size: 100%; background-color: #eee" >
				                  <i class="fa fa-facebook"></i>
				                </a>
			              	</div>
		              	</div>

		              	<div class="col-md-4">
              			</div>
	          		</div>
	          	</div>
        	<div class="container">
        		<div class="row">
        			<div class="col-md-12 text-center">
						&copy; 2015 Zapdel.com. All Rights Reserved.
        			</div>
        		</div>        	
			</div>

			<div class="container">
        		<div class="row footer_links">
        			<div class="col-md-3 text-center">
        					<a onClick="window.location.href='/contact_us'"> Contact Us</a>
        			</div>   
        			<div class="col-md-3 text-center">
        					<a onClick="window.location.href='/about_us'">About Us</a>
        			</div>
        			
        			<div class="col-md-3 text-center">
        					<a onClick="window.location.href='/terms_and_conditions'"> Terms & Conditions</a>
        			</div>
        			<div class="col-md-3 text-center">
        					<a onClick="window.location.href='/privacy_policy'"> Privacy Policy</a>
        			</div>
        			<div class="col-md-3 text-center">
							<a onClick="window.location.href='/cancellations_and_refund'">Cancellations & Refund</a>
         			</div>

         			<div class="col-md-3 text-center">
							<a onClick="window.location.href='/shipping_delivery'"> Shipping &amp Delivery Policy</a>
         			</div>

         			<div class="col-md-3 text-center">
							<a onClick="window.location.href='/faq'">F A Q</a>
         			</div>

         			<div class="col-md-3 text-center">
							<a onClick="window.location.href='/our_social_responsibilites'"> Our Social Responsibilites</a>
         			</div>

        		</div>        	
			</div>
		
      </footer>
	</div>

@endsection
