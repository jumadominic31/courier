@extends('layouts.app')

@section('content')

<a href="{{ route('shipments.index') }}" class="btn btn-success">Shipments Home</a><br>
<div class="panel-heading"><h1>Booked Shipments </h1> </div>
{!! Form::open(['action' => 'TxnsController@assignpickupShipments', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="input-group">
    <span class="input-group-addon" >Rider Name *</span>
    {{Form::select('rider_id', ['' => ''] + $riders, old('rider_id'), ['class' => 'form-control', 'id' => 'rider_id'])}}
</div>
<br>
{{Form::submit('Assign to Rider for Collection', ['class'=>'btn btn-primary', 'id' => 'submit-btn'])}}
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
        <th width="8.33%">Rider</th>
        <th width="8.33%">Mode</th>
        <th width="8.33%">Parcel Status</th>         
        <th width="11.33%">Date/Time Created</th>
    </tr>
    @foreach($txns as $txn)
    <tr>
    	<th><input type="checkbox" name="txn_id[]" value="{{$txn['id']}}"></th>
        <td>{{$txn['sender_company_name']}}</td>
        <td>{{$txn['awb_num']}}</td>
        <td>{{$txn['origin_addr']}}</td>
        <td>{{$txn['dest_addr']}}</td>
        <td>{{$txn['parcel_type']['name']}}</td>
        <td>{{$txn['driver']['fullname']}}</td>
        @if ($txn['mode'] == 0)
        <td>Normal</td>
        @else ($txn['mode'] == 1)
        <td>Express</td>
        @endif
        <td>{{$txn['parcel_status']['name']}}</td>
        <td>{{$txn['created_at']}}</td>
    </tr>
      @endforeach
</table>


@else
	<p>No Transactions To Display</p>
@endif
{!! Form::close() !!}



<!-- Ajax test -->
{{ csrf_field() }}
  <div class="table-responsive text-center">
    <table class="table table-striped" >
    <tr>
        <th width="9%">Sender Company</th>
        <th width="9%">AWB#</th>
        <th width="9%">Origin</th>
        <th width="9%">Destination</th>
        <th width="9%">Parcel Type</th>
        <th width="9%">Mode</th>
        <th width="9%">Parcel Status</th>         
        <th width="9%">Date/Time Created</th>
        <th width="9%">Received</th>
        <th width="9%">Assign Rider</th>
        <th></th>
    </tr>
    @foreach($txns as $txn)
    
        <tr class="txn{{$txn->id}}">
        
            <td>{{$txn['sender_company_name']}}</td>
            <td id="awb_num">{{$txn['awb_num']}}</td>
            <td>{{$txn['origin_addr']}}</td>
            <td>{{$txn['dest_addr']}}</td>
            <td>{{$txn['parcel_type']['name']}}</td>
            @if ($txn['mode'] == 0)
            <td>Normal</td>
            @else ($txn['mode'] == 1)
            <td>Express</td>
            @endif
            <td>{{$txn['parcel_status']['name']}}</td>
            <td>{{$txn['created_at']}}</td>
            <td><input type="checkbox" name="txn_id[]" value="{{$txn['id']}}"></td>
            <td id="driver_id">{{Form::select('driver_id', ['' => ''] + $riders, '', ['class' => 'form-control'])}}</td>
            <td><button class="btn btn-primary" id="updateTxn" data-id="{{$txn->id}}"
                data-awb_num="{{$txn->awb_num}}">Assign Rider</button></td>
        
        </tr>

      @endforeach
    </table>
  </div>

<script>

      $(document).on('click', '#updateTxn', function(e) {
       e.preventDefault();
       $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
       jQuery.ajax({
          url: "{{ url('/shipments/assignpickup') }}",
          method: 'post',
          data: {
             rider_id: jQuery('#driver_id').val(),
             awb_num: jQuery('#awb_num').val()
          },
          success: function(result){
             jQuery('.alert').show();
             jQuery('.alert').html(result.success);
          }
        });


       });
</script>

@endsection

