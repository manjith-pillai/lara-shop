<div class="modal fade" id="SignIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="border: none">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-sm-6" style="">
           <img  class="" src="/../img/assets/zapdel_p.png"/ style="width:150px;margin-left:30px">
           <div style="padding-top: 15px;color:#787878">
           <h4>Why Register?</h4>
           <hr style="margin-bottom: 10px;margin-top:10px;border-color:#ccc">
             <ul>
               <li>Keep track of your orders</li>
               <li>Reduce order time</li>
             </ul>
           </div>
        </div>
        <div class="col-sm-6">

             <ul class="nav nav-tabs" id="myTab">
             <li><a data-target="#register" data-toggle="tab">Register</a></li>
             <li><a data-target="#sign-in" data-toggle="tab">Sign In</a></li>

      </ul>

      <div class="tab-content">
     
        <div class="tab-pane" id="register">
        <div class="register_container">
          <form class="form-horizontal" id="register-form" role="form" method="POST" action="{{ url('/auth/register') }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="form-group" style="margin-bottom: 5px;margin-right: 5px;margin-top: 20px;margin-left: 2px">
              <input type="text" class="form-control" id="name" placeholder="Name"
               name="name">
               <div id="form-errors" style="display: none"></div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;margin-right: 5px;margin-left: 2px">
              <input type="email" class="form-control" id="email" placeholder="Email"
              name="email" value="">
              <div id="form-errors1" style="display: none"></div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;margin-right: 5px;margin-left: 2px">
              <input type="text" class="form-control" id="phone" placeholder="Phone"
              name="phone" value="">
              <div id="form-errors1" style="display: none"></div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;margin-right: 5px;margin-left: 2px">
              <input type="password" class="form-control" name="password" id="password" placeholder="Password">
              <div id="form-errors2" style="display: none"></div>
            </div>

             <div class="form-group" style="margin-bottom: 5px;margin-right: 5px;margin-left: 2px">
              <input type="password" class="form-control" name="password_confirmation" id="confirmed" placeholder="Confirm Password">
              
            </div>

            <button type="submit" id="modal-register" class="btn registerbtn">Register</button>
            </div>
          </form>
        </div>
        <div class="tab-pane" id="sign-in">
        <div class="signin_container">
           <form class="form-horizontal" role="form" id="sign-in-form" method="POST" action="{{ url('/auth/login') }}">
           <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group" style="margin-bottom: 5px;margin-top: 20px;margin-right: 5px;margin-left: 5px">
              <input type="email" class="form-control" name="email" value=""  id="" placeholder="E-mail ID / Username">
              <div id="sign-errors" style="display: none"></div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;margin-right: 5px;margin-left: 5px">
              <input type="password" class="form-control" name="password" placeholder="Password">
             <div id="sign-errors1" style="display: none"></div>
            </div>
           
            
            <button type="submit" id="modal-sign-in" class="btn registerbtn">Sign In</button>

          </form>
           <div class="form-group text-center" style="margin-bottom: 5px;margin-right: 5px;margin-left: 5px">
              <a href="{{url('/password/email')}}">Forgot Your Password?</a>
            </div>
          </div>
        </div>
      
        
      </div>
        </div>
        </div>
      </div>
      
    </div>
  </div>
</div>