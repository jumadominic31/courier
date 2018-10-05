@extends('layouts.app')

@section('content')

<div class="panel-heading"><h1>Shipment Operations</h1> </div>
<div><a class="pull-right btn btn-default" href="{{ route('shipments.booked') }}">Reset</a> </div>
<strong>Filter By Status: </strong>
{!! Form::open(['action' => 'TxnsController@getbookedShipments', 'method' => 'POST']) !!}
<table class="table" width="100%" table-layout="fixed">
  <tbody>
    <tr>
      <td width="33.3%">
        <div class="form-group">
          {{Form::label('parcel_status_id', 'Parcel Status')}}
          {{Form::select('parcel_status_id', ['' => '', '7' => 'Booked', '10' => 'Received at sort facility', '2' => 'Dispatched'], '', ['class' => 'form-control', 'id' => 'parcel_status_id'])}}
        </div>
      </td>
    </tr>
  </tbody>
</table>

{{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
<br><br>
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
        <th></th>
    </tr>
    @foreach($txns as $txn)
    
        <tr>
        
            <td>{{$txn['sender_company_name']}}</td>
            <td class="awb_num">{{$txn['awb_num']}}</td>
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
            <td>
              @if($txn['parcel_status_id'] == 7)
                <input class="check_received" type="checkbox">
                </td>
                <td></td>
              @elseif($txn['parcel_status_id'] == 10)
                <input class="check_received" type="checkbox" checked></td>
                <td class="driver_id"> 
                  {{Form::select('driver_id', ['' => ''] + $riders, '', ['class' => 'form-control input-sm'])}}
                </td>
              @elseif($txn['parcel_status_id'] == 2)
                <input class="check_received" type="checkbox" checked></td>
                <td class="driver_id"> {{Form::select('driver_id', ['' => ''] + $riders, $txn['driver_id'], ['class' => 'form-control input-sm'])}}
                </td>
              @endif
            
            <td><button class="btn btn-primary" id="updateTxn" >Update</button></td>
            @if($txn['parcel_status_id'] == 2)
              <td><button class="btn btn-warning" id="returnTxn" >Return</button></td>
            @else
              <td><button class="btn btn-warning" id="returnTxn" disabled="true">Return</button></td>
            @endif

        </tr>

      @endforeach
    </table>
  </div>

<script>

     
      $(document).on('click', '#updateTxn', function(e) {
       e.preventDefault();
       var currentRow = $(this).closest("tr");
       var driver_id = currentRow.find(":selected", ".driver_id").val();
       var awb_num = currentRow.find(".awb_num").text();
       var check_received = currentRow.find('.check_received').is(":checked");
       $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
       jQuery.ajax({
          url: "{{ url('/shipments/assignpickup') }}",
          method: 'post',
          data: {
             driver_id: driver_id,
             awb_num: awb_num,
             check_received: check_received
          },
          success: function(result){
             window.location.reload();
             //window.location = window.location.href;
          }
        });


       });

      $(document).on('click', '#returnTxn', function(e) {
       e.preventDefault();
       var currentRow = $(this).closest("tr");
       var awb_num = currentRow.find(".awb_num").text();
       window.location = '/shipment/' + awb_num + '/return' ;


       });

      
</script>

@endsection

