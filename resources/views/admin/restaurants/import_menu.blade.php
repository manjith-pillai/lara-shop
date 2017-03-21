@extends('admin.dashboard.layouts.home')
@section('admin_content')
<div class="container-fluid" style="margin-bottom: 180px">
	<ol class="breadcrumb">
    	<li><a href="{{ url('admin/restaurants/index') }}">Restaurant List</a></li>
    	<li><a href="{{ url('admin/restaurants/add') }}">New Restaurant</a></li>
    	<li class="active">Import</li>
	</ol>
<h1>Import</h1>
<hr>
@if (Session::has('message'))
   <div class="alert alert-info fade in">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
   {{ Session::get('message') }}
   </div>
@endif
@if (Session::has('errormsg'))
   <div class="alert alert-danger fade in">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
   {{ Session::get('errormsg') }}
   </div>
@endif
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
			<form action="{{ URL::to('admin/restaurants/importfiles') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
					<div class="form-group @if ($errors->has('import_file')) has-error @endif">
    					<input type="file" name="import_file" />
    					 @if ($errors->has('import_file')) <p class="help-block">{{ $errors->first('import_file') }}</p> @endif
					</div>
					<div class="form-group">
						<button class="btn btn-default" type="submit">Upload File</button>
					</div>
			</form>
		</div>
		<div class="col-md-4 text-center">
			<h4>Sample template</h4>
			<img src="{{asset('img/assets/spreadsheet.png')}}" style="width:250px" alt="spreadsheet">
			<h5 ><a href="{{url('/docs/Restaurant_template.xlsx')}}"><span class="glyphicon glyphicon-download"></span>Download</a></h5>
		</div>
	</div>


</div>
		
			
		
</div>
@stop