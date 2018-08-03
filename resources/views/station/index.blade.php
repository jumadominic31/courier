@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Branches Administration </h1> 

    <a href="{{ route('station.create') }}" class="btn btn-success">Add Branch</a>
<br>
</div>
    @if(count($stations) > 0)
      <?php
        $colcount = count($stations);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
	          <tr>
		          <th>Branch Name</th>
		          <th>Status</th>
		          @if(Auth::user()->usertype == 'superadmin')
	              <th>Company</th>
	              @endif
		          <th></th>
	          </tr>
	          @foreach($stations as $station)
	          <tr>
		          <td>{{$station['name']}}</td>
		          <td><?php if ($station['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
		          @if(Auth::user()->usertype == 'superadmin')
	              <td>{{$station['company']['name']}}</td>
	              @endif
		          <td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('station.edit', ['station' => $station->id ]) }}">Edit</a></span></td>
		      </tr>
	          @endforeach
          </table>

    @else
      <p>No Station To Display</p>
    @endif
@endsection