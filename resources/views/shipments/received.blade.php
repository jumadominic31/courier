@extends('layouts.app')
 <script type="text/javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
  }
</script>
@section('content')

<div class="panel-heading"><h1>Shipments - Received by Customer</h1> </div>

<div><a class="pull-right btn btn-default" href="{{ route('shipments.receivedShipments') }}">Reset</a> </div>
<hr>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">

{!! Form::open(['action' => 'TxnsController@receivedatcusShipments', 'method' => 'POST']) !!}
    <table class="table" width="100%" table-layout="fixed">
      <tbody>
      <tr>
        <td width="50%"><div class="form-group">
              {{Form::label('sender_company_id', 'Customer Company')}}
              {{Form::select('sender_company_id', ['' => ''] + $cuscompanies + [0 => 'Others'] ,'', ['class' => 'form-control'])}}
          </div></td>
        <td width="50%"><div class="form-group">
            {{Form::label('first_date', 'First Booked Date')}}
            {{Form::text('first_date', '', ['class' => ' first_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
          </div></td>
  </tr>
</tbody>
</table>
{{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
{{Form::submit('CreatePDF', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
</div>
<br>
<div id='printBtn'>
    <input type="button" onclick="printDiv('print_received')" value="Print" />
</div>
 <br>
 <div id='print_received'>

    <h3>
        <strong>{{$company_details[0]['name']}} </strong><br>
        {{$company_details[0]['address']}}, {{$company_details[0]['city']}} <br>
        Phone: {{$company_details[0]['phone']}}
    </h3>


    @if(count($txns) > 0)
    <?php
    	$colcount = count($txns);
    	$i = 1;
    ?>
    <table class="table table-striped" >
        <tr>
            <th width="10.33%">Sender Company</th>         
            <th width="11.33%">Date/Time Received</th>
            <th width="8.33%">AWB#</th>
            <th width="14.33%">Origin</th>
            <th width="14.33%">Destination</th>
            <th width="8.33%">Parcel Type</th>
            <th width="8.33%">Receiver Name</th>
            <th width="3.33%">Signature</th>
        </tr>
        @foreach($txns as $txn)
        <tr>
            <td>{{$txn['sender_company_name']}}</td>
            <td>{{$txn['updated_at']}}</td>
            <td>{{$txn['awb_num']}}</td>
            <td>{{$txn['origin_addr']}}</td>
            <td>{{$txn['dest_addr']}}</td>
            <td>{{$txn['parcel_type']['name']}}</td>
            <td>{{$txn['receiver_name']}}</td>
            <td><img src="{{asset('storage/receiver_sign/'. $txn['receiver_sign'] )}}" style="width:100%; max-width:300px;"></td>
        </tr>
          @endforeach
    </table>
    @else
    	<p>No Transactions To Display</p>
    @endif
</div>
@endsection