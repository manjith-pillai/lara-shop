<nav class="navbar navbar-inverse">
<div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" style="padding-top: 8px" href="{{url('admin/order/order-list')}}">Dashboard</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav" style="margin-top:5px">
                <!-- <li class="active"><a href="#">Home</a></li> -->
                <li>
                	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Catalog <b class="caret"></b></a>
                	<ul class="dropdown-menu m">
                		<li><a href="#">Categories</a></li>
                        <li><a href="{{url('admin/restaurants/index')}}">Restaurants</a></li>
                        <li><a href="#">Payments</a></li>
                        <li><a href="#">Options</a></li>
                        <li><a href="#">Reviews</a></li>
                        <li><a href="#">Information</a></li>
                        <li><a href="#">Customer Testimonials</a></li>
                        <li><a href="#">Video Testimonial</a></li>
                	</ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sales <b class="caret"></b></a>
                    <ul class="dropdown-menu m multi-level">
                        <li><a href="{{url('admin/order/order-list')}}">Orders</a></li>
                        <li><a href="#">Recurring Profiles</a></li>
                        <li><a href="#">Returns</a></li> 
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Customers</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Customers</a></li>
                              	<li><a href="#">Customer Groups</a></li>
                              	<li><a href="#">Banned IP</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Affiliates</a></li> 
                        <li><a href="#">Coupons</a></li> 
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Gift Vouchers</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Gift Vouchers</a></li>
                              	<li><a href="#">Voucher Themes</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Mail</a></li> 
                    </ul>
                </li>
				<li>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">System <b class="caret"></b></a>
					<ul class="dropdown-menu m multi-level">
						<li><a href="#">Settings</a></li>
						<li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Design</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Layouts</a></li>
                              	<li><a href="#">Banners</a></li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Users</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Users</a></li>
                              	<li><a href="#">User Groups</a></li>
                            </ul>
                        </li>
                         <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Localisation</a>
                            <ul class="dropdown-menu m">
                                <li><a href="#">Languages</a></li>
                              	<li><a href="#">Currencies</a></li>
                              	<li><a href="#">Stock Statuses</a></li>
                              	<li><a href="#">Order Statuses</a></li>
                              	<li class="dropdown-submenu">
                              		 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Returns</a>
                              		<ul class="dropdown-menu">
                                		<li><a href="#">Return Statuses</a></li>
                              			<li><a href="#">Return Actions</a></li>
                              			<li><a href="#">Return Reasons</a></li>
                            		</ul> 
                              	</li>
                              	<li><a href="#">Countries</a></li>
                              	<li><a href="#">Zones</a></li>
                              	<li><a href="#">Cities</a></li>
                              	<li><a href="#">Areas</a></li>
                              	<li><a href="#">Geo Zones</a></li>
                              	<li class="dropdown-submenu">
                              		 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Taxes</a>
                              		<ul class="dropdown-menu">
                                		<li><a href="#">Tax Classes</a></li>
                              			<li><a href="#">Tax Rates</a></li>
                            		</ul> 
                              	</li>
                              	<li><a href="#">Length Classes</a></li>
                              	<li><a href="#">Weight Classes</a></li>
                            </ul>
                        </li>
						<li><a href="#">Error Logs</a></li>
						<li><a href="#">Backup / Restore</a></li>
						<li><a href="#">Export / Import</a></li>
						<li><a href="{{url('admin/restaurants/import')}}">Import / Export</a></li>
					</ul>
				</li>
				 <li>
                	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                	<ul class="dropdown-menu m">
                        <li>
                            <a href="{{url('admin/Noida/track_boys')}}">Track Delivery Boys</a>
	                  		
                        </li>
                        <li>
                            <a href="{{url('admin/delivery_boys/reports/All')}}">Delivery Boys Reports</a>
                        
                        </li>
                        
                        <li class="dropdown-submenu">
	                  		 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sales</a>
	                  		<ul class="dropdown-menu">
	                    		<li><a href="#">Orders</a></li>
	                  			<li><a href="#">Tax</a></li>
	                  			<li><a href="#">Shipping</a></li>
	                  			<li><a href="#">Returns</a></li>
	                  			<li><a href="#">Coupons</a></li>
	                		</ul> 
                        </li>
                         <li class="dropdown-submenu">
	                  		 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Restaurants</a>
	                  		<ul class="dropdown-menu">
	                    		<li><a href="#">Restaurant Viewed</a></li>
	                  			<li><a href="#">Food Items Purchased</a></li>
	                		</ul> 
                        </li>
                        <li class="dropdown-submenu">
	                  		 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Customers</a>
	                  		<ul class="dropdown-menu">
	                    		<li><a href="#">Customers Online</a></li>
	                  			<li><a href="#">Orders</a></li>
	                  			<li><a href="#">Reward Points</a></li>
	                  			<li><a href="#">Credit</a></li>
	                		</ul> 
                        </li>
                         <li class="dropdown-submenu">
	                  		 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Affiliates</a>
	                  		<ul class="dropdown-menu">
	                    		<li><a href="#">Commission</a></li>
	                  			<li><a href="#">Orders</a></li>
	                  			<li><a href="#">Reward Points</a></li>
	                  			<li><a href="#">Credit</a></li>
	                		</ul> 
                        </li>
                        </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
  </div><!-- /.container-fluid -->
</nav>