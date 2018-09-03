@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1>Shipments by Customer</h1> </div>

<div><a class="pull-right btn btn-default" href="{{ route('shipments.bycustomer') }}">Reset</a> </div>
<hr>
vita
  <table class="table table-striped" >
    <tr>
      <th>Customer</th>
      <th>Jan</th>
      <th>Feb</th>
      <th>Mar</th>
      <th>Apr</th>
      <th>May</th>
      <th>Jun</th>
      <th>Jul</th>
      <th>Aug</th>
      <th>Sep</th>
      <th>Oct</th>
      <th>Nov</th>
      <th>Dec</th>
      <th>Total</th>
    </tr>
    @foreach($txns as $txn)
    <tr>
      <td>{{$txn['sender_company']['name']}}</td>
      <td>{{$txn['Jan']}}</td>
      <td>{{$txn['Feb']}}</td>
      <td>{{$txn['Mar']}}</td>
      <td>{{$txn['Apr']}}</td>
      <td>{{$txn['May']}}</td>
      <td>{{$txn['Jun']}}</td>
      <td>{{$txn['Jul']}}</td>
      <td>{{$txn['Aug']}}</td>
      <td>{{$txn['Sep']}}</td>
      <td>{{$txn['Oct']}}</td>
      <td>{{$txn['Nov']}}</td>
      <td>{{$txn['December']}}</td>
      <td>{{$txn['Total']}}</td>
  </tr>
    @endforeach
  </table>

@endsection