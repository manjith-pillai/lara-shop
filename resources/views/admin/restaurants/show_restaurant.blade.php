@extends('admin.dashboard.layouts.home')
@section('admin_content')
<div class="search_container">
    
    <form action="{{url('admin/restaurants/index')}}" method="get">
        <div class="search_input">
            <input type="text" value="{{ isset($q) ? $q : '' }}" class="form-control " id="Search" placeholder="Search by Restaurant Name or Address or City or Phone no." name="query">
        </div>
            <input type="submit" class="btn btn-default search_btn" value="Search">
            <a href="/admin/restaurants/index" class="btn btn-default search_btn">Reset</a>
    </form>

</div>
<div class="container-fluid">
@if(Session::has('flash_message'))
    <div class="alert alert-success fade in" style="margin-top: 10px">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_message') }}
    </div>
@endif

<div class="panel panel-default">
  <div class="panel-heading">
    <span style="font-size:24px"> Restaurant List</span>
  <a href="{{url('admin/restaurants/add')}}" style="float: right;"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add Restaurant & Menu</a>
    
  </div>

  <div class="table-responsive">
  <table class="table table-striped">
    <thead>
    	<tr>
            <th>ID</th>
    		<th>Restaurant Name</th>
    		<th>Address</th>
            <th>City</th>
    		<th>Phone</th>
    		<th>Contact Name</th>
    		<th>Contact Phone</th>
    		<th>Action</th>
            <th>Status</th>
    	</tr>
    </thead>
    <tbody>
    	@foreach($restaurant as $r)
    	<tr>
            <td>{{ $r->id }}</td>
    		<td>{{ $r->name }}</td>
    		<td>{{ $r->address }}</td>
            <td>{{ $r->city_name}}</td>
    		<td>{{ $r->phone }}</td>
    		<td>{{ $r->contact_person }}</td>
    		<td>{{ $r->contact_person_phone }}</td>
            <td><a href="{{url('admin/restaurants/edit/'.$r->id)}}">Edit/Update</a>
            </td>
            <td>  
            @if($r->is_active == "open")
            <form method="POST" class="open" action="{{url('admin/restaurants/update/open/'.$r->id)}}">
            <input type="hidden" name="_method" value="PATCH">
                <button class="btn-success" type="submit">Open</button>
            </form>
            @else
            <form method="POST" class="closed" action="{{url('admin/restaurants/update/closed/'.$r->id)}}">
            <input type="hidden" name="_method" value="PATCH">
                <button class="btn-danger" type="submit">Closed</button>
            </form>
            </td>
            @endif
            
    	</tr>
		@endforeach
    </tbody>
  </table>

{!! $restaurant->appends(['query' => $q])->render() !!}
  </div>
</div>

<script>

  $(".open").on("submit", function(){
        return confirm("Do you want to close this Restaurant?");
    });
  $(".closed").on("submit", function(){
        return confirm("Do you want to open this Restaurant?");
    });

</script>

</div>
@stop