<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, height=device-height">
    <meta name="google" content="notranslate" />
    <link rel="icon" href="{{asset('ico/favicon.ico')}}">
    <title>{{(isset($title) && $title) ? $title : "Zapdel: Food Delivery | Restaurant Takeout | Order Online"}} </title>

    <meta name="keywords" content="{{(isset($keywords) && $keywords)  ? $keywords : 'Free Food Delivery Service ,Restaurants in vadodara,restaurants in Indore,restaurants in Surat,restaurants in Noida, restaurants in baroda, best food in Lucknow, food delivery in Lucknow, free food home delivery food in Lucknow, home delivery food Lucknow, online food order vadodara, home delivery restaurants in Lucknow, food delivery in Indore, food delivery service Surat, free home delivery food in Surat, home delivery food Indore, home delivery food in Indore, online food order Indore, home delivery restaurants in Indore, online food delivery in indore, fast food restaurants in Noida, best restaurants in Surat, best food in Indore, restaurants in Indore'}}">

    <meta name="description" content="{{(isset($description) && $description) ? $description :'Free food delivery | Vadodara ,Indore ,Surat, Lucknow, Noida, Ahmedabad , Ghaziabad | Order food online or via Mobile App |Free food delivery and take away from 300+ Restaurants with Zapdel Food Delivery Service'}}">



    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


    <!-- <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'> -->
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,300italic' rel='stylesheet' type='text/css'>

    <!-- Custom -->
    <link rel="stylesheet" href="{{asset('css/stylen.css')}}">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
      <script src="{{asset('js/google-analytics.js')}}"></script>
      <script src="{{asset('js/stickit.js')}}"></script>
      <script src="{{asset('js/auth.js')}}"></script>
<!--      <script src="{{asset('js/hotjar.js')}}"></script>-->
      @yield('checkout_redirect')
    <script>
      var full_url_asset = '{{asset("img/assets")}}';
    </script>
    @yield('order_track_success')
    <!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '168166550250201');
      fbq('track', "PageView");
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=168166550250201&ev=PageView&noscript=1"/>
    </noscript>
<!-- End Facebook Pixel Code -->
  </head>
  <body>
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
          <li><div class="appBtn"><a href="https://play.google.com/store/apps/details?id=com.zapdel&hl=en" target="_blank"> <img style="height: 20px;margin-right: 10px;" src="{{asset('img/assets/androidicon.png')}}" alt="">download app</a><div></li>
           @if (Auth::guest())
            <li class="sign-in" data-toggle="modal" data-target="#SignIn"><a href="#">Sign in</a></li>
            @else
            <li><a href = "/">Welcome {{ Auth::user()->name }}</a></li>
                <li><a href="/auth/logout">Logout</a></li>
            @endif
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
</nav>
<!-- End Navigation -->

  @yield('content')
   
  @include('footern')


  </body>
</html>