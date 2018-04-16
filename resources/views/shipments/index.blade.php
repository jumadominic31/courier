@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1>Manage Shipments </h1> </div>
<div><a class="pull-right btn btn-default" href="{{ route('shipments.index') }}">Reset</a> </div>
<hr>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
  {!! Form::open(['action' => 'TxnsController@getShipments', 'method' => 'POST']) !!}
    <table class="table" width="100%" table-layout="fixed">
      <tbody>
      <tr>
        <td width="33.3%">
          <div class="form-group">
              {{Form::label('awb_num', 'AWB #')}}
              {{Form::text('awb_num', '', ['class' => 'form-control'])}}
          </div></td>
          <td width="33.3%"><div class="form-group">
              {{Form::label('origin_id', 'Origin')}}
              {{Form::select('origin_id', ['' => ''] + $zones, '', ['class' => 'form-control', 'id' => 'origin_id'])}}
          </div></td>
          <td width="33.3%"><div class="form-group">
              {{Form::label('dest_id', 'Destination')}}
              {{Form::select('dest_id', ['' => ''] + $zones, '', ['class' => 'form-control', 'id' => 'dest_id'])}}
          </div></td>
      </tr>
      <tr>
        <td><div class="form-group">
              {{Form::label('sender_company_id', 'Customer Company')}}
              {{Form::select('sender_company_id', ['' => ''] + $cuscompanies + [0 => 'Others'] ,'', ['class' => 'form-control'])}}
          </div></td>
        <td><div class="form-group">
              {{Form::label('rider_id', 'Rider')}}
              {{Form::select('rider_id', ['' => ''] + $riders, '', ['class' => 'form-control', 'id' => 'rider_id'])}}
          </div></td>
        <td><div class="form-group">
              {{Form::label('parcel_status_id', 'Parcel Status')}}
              {{Form::select('parcel_status_id', ['' => ''] + $parcel_status, '', ['class' => 'form-control', 'id' => 'parcel_status_id'])}}
          </div></td>
      </tr>
      <tr>
        <td><div class="form-group">
              {{Form::label('first_date', 'First Date')}}
              {{Form::text('first_date', '', ['class' => ' first_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
          </div></td>
        <td><div class="form-group">
            {{Form::label('last_date', 'Last Date')}}
            {{Form::text('last_date', '', ['class' => 'last_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
        </div></td>
        <td></td>
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
            <h4>Total Sales</h4>
          </div>
        </div>
      </div>
    </div>
      <table class="table table-striped" >
          <tr>
	          <th>AWB#</th>
            <th>Cust Company</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Parcel Type</th>
            <th>Price</th>
            <th>VAT</th>
            <th>Sender Name</th>
            <th>Sender ID#</th>
            <th>Sender Phone</th>
            <th>Receiver Name</th>
            <th>Receiver ID#</th>
            <th>Receiver Phone</th>
		    <th>Date Created</th>
            <th>Parcel Status</th>
            <th>Rider</th>
          </tr>
          @foreach($txns as $txn)
          <tr class='clickable-row' data-href="{{ route('shipments.edit', ['awb' => $txn->id ]) }}">
	            <td>{{$txn['awb_num']}}</td>
              @if ($txn['sender_company_id'] == 0)
                <td>{{$txn['sender_company_name']}}</td>
              @else
                <td>{{$txn['sender_company']['name']}}</td>
              @endif
              <td>{{$txn['zone_origin']['name']}}</td>
              <td>{{$txn['zone_dest']['name']}}</td>
              <td>{{$txn['parcel_type']['name']}}</td>
              <td>{{$txn['price']}}</td>
              <td>{{$txn['vat']}}</td>
              <td>{{$txn['sender_name']}}</td>
              <td>{{$txn['sender_id_num']}}</td>
              <td>{{$txn['sender_phone']}}</td>
              <td>{{$txn['receiver_name']}}</td>
              <td>{{$txn['receiver_id_num']}}</td>
              <td>{{$txn['receiver_phone']}}</td>
              <td>{{$txn['created_at']}}</td>
              <td>{{$txn['parcel_status']['name']}}</td>
              <td>{{$txn['rider']['fullname']}}</td>
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