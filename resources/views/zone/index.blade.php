@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Zones Administration </h1> 

    <a href="{{ route('zone.create') }}" class="btn btn-success">Add Zone</a>
<br>
</div>
    @if(count($zones) > 0)
      <?php
        $colcount = count($zones);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
	          <tr>
		          <th>Zone Name</th>
		          <th>Status</th>
		          @if(Auth::user()->usertype == 'superadmin')
	              <th>Company</th>
	              @endif
		          <th></th>
		          <th></th>
		          <th></th>
	          </tr>
	          @foreach($zones as $zone)
	          <tr>
		          <td>{{$zone['name']}}</td>
		          <td><?php if ($zone['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
		          @if(Auth::user()->usertype == 'superadmin')
	              <td>{{$zone['company']['name']}}</td>
	              @endif
		          <td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('zone.show', ['zone' => $zone->id ]) }}">Stations</a></span></td>
		          <td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('zone.edit', ['zone' => $zone->id ]) }}">Edit</a></span></td>
		          <td><span class="center-block">
		            {!!Form::open(['action' => ['ZonesController@destroy', $zone->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
		              {{Form::hidden('_method', 'DELETE')}}
		              {{Form::submit('Delete', ['class' => 'btn btn-danger btn-xs'])}}
		            {!! Form::close() !!}
		          </span></td>
		      </tr>
	          @endforeach
          </table>

    @else
      <p>No Zone To Display</p>
    @endif
@endsection