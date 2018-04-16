@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Stations for {{$zone_name}}</h1> 

    <a href="{{ route('zone.index') }}" class="btn btn-success">Back</a>
<br>
</div>
    @if(count($stations) > 0)
      <?php
        $colcount = count($stations);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
	          <tr>
		          <th>Station Name</th>
		          <th>Zone</th>
		          <th>Status</th>
		          @if(Auth::user()->usertype == 'superadmin')
	              <th>Company</th>
	              @endif
		          <th></th>
		          <th></th>
	          </tr>
	          @foreach($stations as $station)
	          <tr>
		          <td>{{$station['name']}}</td>
		          <td>{{$station['zone']['name']}}</td>
		          <td><?php if ($station['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
		          @if(Auth::user()->usertype == 'superadmin')
	              <td>{{$station['company']['name']}}</td>
	              @endif
		          <td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('station.edit', ['station' => $station->id ]) }}">Edit</a></span></td>
		          <td><span class="center-block">
		            {!!Form::open(['action' => ['StationsController@destroy', $station->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
		              {{Form::hidden('_method', 'DELETE')}}
		              {{Form::submit('Delete', ['class' => 'btn btn-danger btn-xs'])}}
		            {!! Form::close() !!}
		          </span></td>
		      </tr>
	          @endforeach
          </table>

    @else
      <p>No Station To Display</p>
    @endif
@endsection