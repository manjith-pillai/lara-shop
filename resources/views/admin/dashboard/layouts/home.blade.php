<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, height=device-height">
    <meta name="google" content="notranslate" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('ico/favicon.ico')}}">
	<title>Admin Panel</title>
	<meta name="keywords" content="">
    <meta name="description" content="">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
     <link href='https://fonts.googleapis.com/css?family=Lato:400,300,300italic' rel='stylesheet' type='text/css'>
       <!-- Custom -->
    <link rel="stylesheet" href="{{asset('css/stylen.css')}}">
    <link rel="stylesheet" href="{{asset('css/timepicki.css')}}">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="{{asset('js/auth.js')}}"></script>
  	<script src="{{asset('js/hotjar.js')}}"></script>
    <script src="{{asset('js/timepicki.js')}}"></script>
</head>
<body>
	 <!-- Main Navigation -->
    
    <nav class="navbar navbar-default">
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
@include('admin.dashboard.layouts._nav')
@yield('admin_content')
@include('footern')
</body>
</html>