@if( empty($loggedin) || empty($userid) || $userid == -1)

<ul class="nav navbar-nav navbar-right ">
    <li><a id="signIn" onclick="PersonalDetails.controllers.displaysignin()" class="col-xs-5" style="text-align: right; margin-top: 5px; cursor: pointer" >SignIn</a></li>
		  <div class="col-md-3" style="z-index=2;width:283px;margin-top: 24px; padding: 12px; background-color: #F9F9F9; color: #975ba5;display: none" id="signIn-page">
				@include('auth.login')
		  </div>

    <li><a id="signUp" onclick="PersonalDetails.controllers.displaysignup()" >SignUp</a></li>
        
          <div class="col-md-3" style="z-index=2;width:283px;margin-top: 24px; padding: 12px; background-color: #F9F9F9; color: #975ba5;display: none" id="signUp-page">
                @include('auth.register')
          </div>
@else
    <div>
            Welcome {{$userid}}
    </div>
@endif