@extends('layouts.cusapp')

@section('content')
<div class="panel-heading"><h1>Manage Shipments </h1> </div>
<div><a class="pull-right btn btn-default" href="{{ route('portal.shipments.index') }}">Reset</a> </div>
<hr>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
  {!! Form::open(['action' => 'CusportalController@getShipments', 'method' => 'POST']) !!}
    <table class="table" width="100%" table-layout="fixed">
      <tbody>
      <tr>
        <td width="33.3%">
          <div class="form-group">
              {{Form::label('awb_num', 'AWB #')}}
              {{Form::text('awb_num', '', ['class' => 'form-control'])}}
          </div></td>
          <td width="33.3%"><div class="form-group">
            {{Form::label('first_date', 'Booked Date')}}
            {{Form::text('first_date', '', ['class' => ' first_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
          </div></td>
          <td width="33.3%"><div class="form-group">
            {{Form::label('last_date', 'Booked Date')}}
            {{Form::text('last_date', '', ['class' => 'last_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
          </div></td>
      </tr>
      <tr>
        <td><div class="form-group">
              {{Form::label('sender_name', 'Sender Name')}}
              {{Form::text('sender_name', '', ['class' => 'form-control'])}}
          </div></td>
        <td><div class="form-group">
              {{Form::label('receiver_name', 'Receiver Name')}}
              {{Form::text('receiver_name', '', ['class' => 'form-control'])}}
          </div></td>
        <td><div class="form-group">
              {{Form::label('parcel_status_id', 'Parcel Status')}}
              {{Form::select('parcel_status_id', ['' => ''] + $parcel_status, '', ['class' => 'form-control', 'id' => 'parcel_status_id'])}}
          </div></td>
      </tr>
      
      </tbody>
    </table>
    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
    {{Form::submit('CreatePDF', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
</div>
<hr>
@if(count($txns) > 0)
  <?php
    $colcount = count($txns);
    $i = 1;
  ?>
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="well dash-box">
            <h2><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> 
            {{$tot_coll}} </h2>
            <h4>Total Cost</h4>
          </div>
        </div>
      </div>
    </div>
      <table class="table table-striped" >
          <tr>
	          <th>AWB#</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Parcel Type</th>
            <th>Price</th>
            <th>VAT</th>
            <th>Sender Name</th>
            <th>Receiver Name</th>
            <th>Receiver Company</th>
		        <th>Date/Time Created</th>
            <th>Parcel Status</th>
            <th>Round Trip</th>
          </tr>
          @foreach($txns as $txn)
          <tr class='clickable-row' data-href="{{ route('portal.shipments.edit', ['awb' => $txn->id ]) }}">
	            <td>{{$txn['awb_num']}}</td>
              <td>{{$txn['origin_addr']}}</td>
              <td>{{$txn['dest_addr']}}</td>
              <td>{{$txn['parcel_type']['name']}}</td>
              <td>{{$txn['price']}}</td>
              <td>{{$txn['vat']}}</td>
              <td>{{$txn['sender_name']}}</td>
              <td>{{$txn['receiver_name']}}</td>
              <td>{{$txn['receiver_company_name']}}</td>
              <td>{{$txn['updated_at']}}</td>
              <td>{{$txn['parcel_status']['name']}}</td>
              @if ($txn['round'] == 0)
              <td>No</td>
              @else ($txn['round'] == 1)
              <td>Yes</td>
              @endif
	      </tr>
          @endforeach
      </table>

@else
  <p>No Transactions To Display</p>
@endif

<script type="text/javascript">
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
@endsection