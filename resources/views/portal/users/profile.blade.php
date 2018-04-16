@extends('layouts.cusapp')

@section('content')
<div class="container">
      <!-- PANELS -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><strong>Company and User Profile</strong></h3>
        </div>
        <div class="panel-body">
        	<div class="row">
        		<div class="col-md-6">
			        <table class="table table-striped">
			          <tr><td>Username</td><td>{{$user['username']}}</td></tr>
			          <tr><td>Full Name</td><td>{{$user['fullname']}}</td></tr>
			          <tr><td>Phone Number</td><td>{{$user['phone']}}</td></tr>
			          <tr><td>Email Address</td><td>{{$user['email']}}</td></tr>
			          <tr><td>Station</td><td>{{$user['station']['name']}}</td></tr>
			          <tr><td>User Type</td><td>{{$user['usertype']}}</td></tr>
			          <tr><td>Status</td><td> 
			          	@if ($user['status'] == 1)
			      			Active
			      		@else
			      			Inactive
			      		@endif
			      		</td></tr>  
					
			        </table>
			    </div>
			    <div class="col-md-6">
			        <table class="table table-striped">
			          <tr><td>Name</td><td>{{$company[0]['name']}}</td></tr>
			          <tr><td>Address</td><td>{{$company[0]['address']}}</td></tr>
			          <tr><td>City</td><td>{{$company[0]['city']}}</td></tr>
			          <tr><td>PIN #</td><td>{{$company[0]['pin']}}</td></tr>
			          <tr><td>Phone</td><td>{{$company[0]['phone']}}</td></tr>
			          <tr><td>Email Address</td><td>{{$company[0]['email']}}</td></tr>
			          <tr><td>Status</td><td> 
			          	@if ($company[0]['status'] == 1)
			      			Active
			      		@else
			      			Inactive
			      		@endif
			      		</td></tr>  
					
			        </table>
			    </div>
	    	</div>
    	</div>
    	
    	<div class="panel-footer">
        	<a href="{{ route('portal.users.edit', ['user' => $user->id ]) }}" class="btn btn-success">Edit User Details</a>
        	<a href="{{ route('portal.users.resetindividualpass') }}" class="btn btn-success">Reset Password</a>
        	@if(Auth::user()->usertype != 'cusclerk')
        		<a href="{{ route('portal.company.edit', ['company' => $company[0]['id'] ]) }}" class="btn btn-success">Edit Company Details</a>
        	@endif
        </div>
      
        
	</div>
</div>

@endsection