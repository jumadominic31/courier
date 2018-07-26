@extends('layouts.app')

@section('content')

<a href="{{ route('shipments.index') }}" class="btn btn-success">Shipments Home</a><br>
<div class="panel-heading"><h1>Shipments - Received at Sort Facility</h1> </div>
{!! Form::open(['action' => 'TxnsController@dispatchShipments', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="input-group">
    <span class="input-group-addon" >Rider Name *</span>
    {{Form::select('rider_id', ['' => ''] + $riders, old('rider_id'), ['class' => 'form-control', 'id' => 'rider_id'])}}
</div>
<br>
{{Form::submit('Assign to Rider for Delivery', ['class'=>'btn btn-primary', 'id' => 'submit-btn'])}}
<br><br>
@if(count($txns) > 0)
<?php
	$colcount = count($txns);
	$i = 1;
?>
<table class="table table-striped" >
    <tr>
    	<th></th>
        <th width="10.33%">Sender Company</th>
        <th width="9.33%">AWB#</th>
        <th width="13.33%">Origin</th>
        <th width="13.33%">Destination</th>
        <th width="8.33%">Parcel Type</th>
        <th width="4.33%">Price</th>
        <th width="4.33%">VAT</th>
        <th width="8.33%">Rider</th>
        <th width="8.33%">Mode</th>
        <th width="8.33%">Parcel Status</th>         
        <th width="11.33%">Date/Time Created</th>
        <th width="3.33%">Invoiced</th>
    </tr>
    @foreach($txns as $txn)
    <tr>
    	<th><input type="checkbox" name="txn_id[]" value="{{$txn['id']}}"></th>
        <td>{{$txn['sender_company_name']}}</td>
        <td>{{$txn['awb_num']}}</td>
        <td>{{$txn['origin_addr']}}</td>
        <td>{{$txn['dest_addr']}}</td>
        <td>{{$txn['parcel_type']['name']}}</td>
        <td>{{$txn['price']}}</td>
        <td>{{$txn['vat']}}</td>
        <td>{{$txn['driver']['fullname']}}</td>
        @if ($txn['mode'] == 0)
        <td>Normal</td>
        @else ($txn['mode'] == 1)
        <td>Express</td>
        @endif
        <td>{{$txn['parcel_status']['name']}}</td>
        <td>{{$txn['created_at']}}</td>
        @if ($txn['invoiced'] == 0)
        <td>No</td>
        @else ($txn['invoiced'] == 1)
        <td>Yes</td>
        @endif
    </tr>
      @endforeach
</table>
@else
	<p>No Transactions To Display</p>
@endif
{!! Form::close() !!}

@endsection