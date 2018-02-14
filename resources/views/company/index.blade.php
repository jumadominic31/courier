@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Companies Administration </h1> 

    <a href="{{ route('company.create') }}" class="btn btn-success">Add Company</a>
<br>
</div>
    @if(count($companies) > 0)
      <?php
        $colcount = count($companies);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
	          <tr>
		          <th>Id</th>
		          <th>Company Name</th>
		          <th>Address</th>
		          <th>City</th>
		          <th>PIN</th>
		          <th>Phone</th>
		          <th>Email</th>
		          <th>Logo</th>
		          <th>Status</th>
		          <th></th>
		          <th></th>
	          </tr>
	          @foreach($companies as $company)
	          <tr>
		    	  <td>{{$company['id']}}</td>
		          <td>{{$company['name']}}</td>
		          <td>{{$company['address']}}</td>
		          <td>{{$company['city']}}</td>
		          <td>{{$company['pin']}}</td>
		          <td>{{$company['phone']}}</td>
		          <td>{{$company['email']}}</td>
		          <td>{{$company['logo']}}</td>
		          <td><?php if ($company['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
		          <td><span class="center-block"><a class="pull-right btn btn-default" href="{{ route('company.edit', ['company' => $company->id ]) }}">Edit</a></span></td>
		          <td><span class="center-block">
		            {!!Form::open(['action' => ['CompaniesController@destroy', $company->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
		              {{Form::hidden('_method', 'DELETE')}}
		              {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
		            {!! Form::close() !!}
		          </span></td>
		      </tr>
	          @endforeach
          </table>

    @else
      <p>No Company To Display</p>
    @endif
@endsection