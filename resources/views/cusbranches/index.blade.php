@extends('layouts.app')

@section('content')
<div class="panel-heading">
<h1> Customer Branch Administration for {{$company_name}} </h1>
    <a href="{{ route('customer.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a>
    <a href="{{ route('cusbranches.create', ['company_id' => $company_id ]) }}" class="btn btn-success">Add Branch</a>
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
                  <th>Building Name</th>
                  <th>Floor no/Office no</th>
                  <th>Street/Road Name</th>
                  <th>Area Name</th>
                  <th>Status</th>
                  <th></th>
              </tr>
              @foreach($stations as $station)
              <tr>
                  <td>{{$station['name']}}</td>
                  <td>{{$station['building']}}</td>
                  <td>{{$station['floor_office']}}</td>
                  <td>{{$station['street']}}</td>
                  <td>{{$station['area']}}</td>
                  <td><?php if ($station['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
                  <td><span class="center-block"><a class="pull-right btn btn-default btn-xs" href="{{ route('cusbranches.editCusbranch', ['station' => $station->id ]) }}">Edit</a></span></td>
              </tr>
              @endforeach
          </table>

    @else
      <p>No Station To Display</p>
    @endif
@endsection