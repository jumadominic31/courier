@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Contracts Administration </h1> 

    <a href="{{ route('contracts.create') }}" class="btn btn-success">Add Contract</a>
<br>
</div>
    @if(count($contracts) > 0)
      <?php
        $colcount = count($contracts);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
	          <tr>
		          <th>Contract Num</th>
		          <th>Company Name</th>
		          <th>Start Date</th>
		          <th>End Date</th>
		          <th>Min Charge</th>
		          <th>Shipment Limit</th>
		          <th>Cost Overlimit</th>
		          <th>Status</th>
		          <th></th>
	          </tr>
	          @foreach($contracts as $contract)
	          <tr>
		    	  <td>{{$contract['contract_num']}}</td>
		          <td>{{$contract['company']['name']}}</td>
		          <td>{{$contract['start_date']}}</td>
		          <td>{{$contract['end_date']}}</td>
		          <td>{{$contract['min_charge']}}</td>
		          <td>{{$contract['txns_limit']}}</td>
		          <td>{{$contract['txn_cost_overlimit']}}</td>
		          <td><?php if ($contract['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
		          <td><span class="center-block"><a class="pull-right btn btn-default" href="{{ route('contracts.edit', ['contract' => $contract->id ]) }}">Edit</a></span></td>
		      </tr>
	          @endforeach
          </table>

    @else
      <p>No Contract To Display</p>
    @endif
@endsection