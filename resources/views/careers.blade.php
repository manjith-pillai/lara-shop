@extends('layoutn.main')
@section('content')
@include('sign-in-modal')
<div class="container" style="padding-top: 70px;">
			<h1>Zapdel Careers</h1>
		  <hr>
<div class="col-md-12 text-center">
	<blockquote>
  		<p>"In future where time is money, the wealthy can live forever"</p>
  		<footer>Will Salas, <cite title="Movie: In time">Movie: In time</cite></footer>
	</blockquote>
</div>	
<div class="col-md-12">
	<p>If you have the right talent & are passionate about working towards a cause with a single-minded focus, this is the right place for you to show to the world what you are capable of.</p>

<p> We are obsessed about work-talent fit and entrust ownership with every single team member right at the beginning. We do not believe in hierarchy and are open-minded people listening to ideas coming from any corner. At the same time we respect the vision of the company in unison. We are honest and transparent.
</p>
<p>Indefinite passion. Right talent. Entrepreneurial enthusiasm. If you have them all – we invite you to join hands with us and make this world a healthy place to live in! </p>
</div>
<div class="col-md-12 text-center" style="margin-top:50px;margin-bottom:40px">
	<p>Please connect with us on Linkedin to know more about the founders.</p>
	<p><a href="https://www.linkedin.com/company/boibanit-com" target="_blank">https://www.linkedin.com/company/boibanitcom</a></p>
	<a href="{{url('/career-list')}}" class="btn btn-default">Go To Open Positions</a>
</div>
		

	</div>


@stop