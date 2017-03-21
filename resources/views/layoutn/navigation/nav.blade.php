<!-- Main Navigation -->
    
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">
            <img src="{{asset('img/assets/zap_logonew.png')}}" class="logo" alt="">
          </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          
          
          <ul class="nav navbar-nav navbar-right">
           @if (Auth::guest())
            <li class="sign-in" data-toggle="modal" data-target="#SignIn"><a href="#">Sign In</a></li>
            @else
            <li><a href = "/">Welcome {{ Auth::user()->name }}</a></li>
                <li><a href="/auth/logout">Logout</a></li>
            @endif
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
</nav>
<!-- End Navigation -->