 <!-- NAVBAR
      ============================== -->
      <nav class="navbar-fixed-top">
	  <div class="navbar">
        <div class="container">
          <div class="navbar-header">
            <a class="navbar" href="/admin/order/order-list"><img  class="zap_logo" src="/../img/assets/zap_logo.png"/><div style="margin-left: 8px;display: inline-block;"><img src="{{URL::asset('img/assets/zapdelw1.png')}}" alt="" style="max-height: 26px"></div></a>
          </div>
		  <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left ">
              <li>
                <a href="#" class="js-toggle-sidebar">
                  <span class="oi oi-menu hidden-xs"></span>
                  <span class="visible-xs">Bonus pages</span>
                </a>
              </li>
            </ul>
            
			      <ul class="nav navbar-nav navbar-right link-hover">
              @if (Auth::guest())
                <li><a href="javascript:openlogin()" class="col-xs-5" style="text-align: right; margin-top: 0px; cursor: pointer" >SignIn</a></li>
			          <li><a href="javascript:openregister()" class="col-xs-5" style="text-align: right; margin-top: 0px; cursor: pointer" >SignUp</a></li>
              @else
                <li style="color:#ffffff"><a href = "/">Welcome {{ Auth::user()->name }}</a></li>
                <li style="color:#ffffff ; margin-top: 5px ">|</li>
                <li><a href="/auth/logout" class="col-xs-5" style="text-align: center; margin-top: 0px; cursor: pointer;width: 80px" >Logout</a></li>
               @endif
            </ul>
            

          </div><!--/.nav-collapse -->
		
        </div>
		</div>
	</nav>