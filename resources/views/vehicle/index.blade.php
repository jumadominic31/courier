@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Vehicles Administration </h1> 

    <a href="{{ route('vehicle.create') }}" class="btn btn-success">Add Vehicle</a>
<br>
</div>
    @if(count($vehicles) > 0)
      <?php
        $colcount = count($vehicles);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
	          <tr>
		          <th>Vehicle Name</th>
		          <th>Status</th>
		           @if(Auth::user()->usertype == 'superadmin')
	              <th>Company</th>
	              @endif
		          <th></th>
		          <th></th>
	          </tr>
	          @foreach($vehicles as $vehicle)
	          <tr>
		          <td>{{$vehicle['name']}}</td>
		          <td><?php if ($vehicle['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
		          @if(Auth::user()->usertype == 'superadmin')
	              <td>{{$vehicle['company']['name']}}</td>
	              @endif
		          <td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('vehicle.edit', ['vehicle' => $vehicle->id ]) }}">Edit</a></span></td>
		          <td><span class="center-block">
		            {!!Form::open(['action' => ['VehiclesController@destroy', $vehicle->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
		              {{Form::hidden('_method', 'DELETE')}}
		              {{Form::submit('Delete', ['class' => 'btn btn-danger btn-xs'])}}
		            {!! Form::close() !!}
		          </span></td>
		      </tr>
	          @endforeach
          </table>

    @else
      <p>No Vehicle To Display</p>
    @endif
@endsection