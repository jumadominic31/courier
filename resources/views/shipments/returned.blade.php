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

<div class="panel-heading"><h1>Shipments - Returned to Sender</h1> </div>

<div><a class="pull-right btn btn-default" href="{{ route('shipments.returnedShipments') }}">Reset</a> </div>
<hr>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">

{!! Form::open(['action' => 'TxnsController@returnedShipments', 'method' => 'POST']) !!}
    <table class="table" width="100%" table-layout="fixed">
      <tbody>
      <tr>
        <td width="50%"><div class="form-group">
              {{Form::label('sender_company_id', 'Customer Company')}}
              {{Form::select('sender_company_id', ['' => ''] + $cuscompanies + [0 => 'Others'] ,'', ['class' => 'form-control'])}}
          </div></td>
        <td width="50%"><div class="form-group">
            {{Form::label('first_date', 'Returned Date')}}
            {{Form::text('first_date', '', ['class' => ' first_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
          </div></td>
  </tr>
</tbody>
</table>
{{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
{{Form::submit('CreatePDF', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
</div>
<br>

 <br>
 <div id='print_received'>

    @if(count($txns) > 0)
    <?php
    	$colcount = count($txns);
    	$i = 1;
    ?>
    <table class="table table-striped" >
        <tr>
            <th width="10%">Sender Company</th>         
            <th width="10%">Date Returned</th>
            <th width="10%">AWB#</th>
            <th width="15%">Origin</th>
            <th width="15%">Destination</th>
            <th width="10%">Parcel Type</th>
            <th width="10%">Receiver Name</th>
            <th width="10%">Return Reason</th>
            <th width="10%"></th>
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
            <td>{{$txn['return_reason']}}</td>
            <td><a class="pull-right btn btn-default btn-xs" target="_blank" href="{{ route('shipments.returnPrint', ['awb' => $txn->id ]) }}">Print</td>
        </tr>
          @endforeach
    </table>
    @else
    	<p>No Transactions To Display</p>
    @endif
</div>
@endsection