@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1>Manage Invoices </h1> </div>
<a href="{{ route('invoice.add') }}" class="btn btn-success">Create Invoice</a><br>
<br><br>
<a href="{{ route('invoice.add2') }}" class="btn btn-success">Create Invoice - NEW</a><br>
<div><a class="pull-right btn btn-default" href="{{ route('invoice.index') }}">Reset</a> </div>
<hr>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
  {!! Form::open(['action' => 'InvoicesController@getInvoices', 'method' => 'POST']) !!}

    <table class="table" width="100%" table-layout="fixed">
      <tbody>
        <tr>
          <td width="50%"><div class="form-group">
            {{Form::label('invoice_num', 'Invoice #')}}
            {{Form::text('invoice_num', '', ['class' => 'form-control'])}}
          </div></td>
          <td width="50%"><div class="form-group">
            {{Form::label('sender_company_id', 'Customer Company')}}
            {{Form::select('sender_company_id', ['' => ''] + $cuscompanies + [0 => 'Others'] ,'', ['class' => 'form-control'])}}
          </div></td>
        </tr>
        <tr>
          <td ><div class="form-group">
            {{Form::label('first_date', 'First Booked Date')}}
            {{Form::text('first_date', '', ['class' => ' first_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
          </div></td>
          <td ><div class="form-group">
            {{Form::label('last_date', 'End Booked Date')}}
            {{Form::text('last_date', '', ['class' => 'last_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
          </div></td>
        </tr>
      </tbody>
    </table>
    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
    {{Form::submit('CreatePDF', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
    {{Form::close()}}
</div>
<hr>
@if(count($invoices) > 0)
  <?php
    $colcount = count($invoices);
    $i = 1;
  ?>
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="well dash-box">
            <h2><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> 
            {{number_format($tot_amount, 2, '.', ',')}}</h2>
            <h4>Total Amount</h4>
        </div>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="well dash-box">
            <h2><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> 
            {{number_format($tot_paid, 2, '.', ',')}} </h2>
            <h4>Total Paid</h4>
        </div>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="well dash-box">
            <h2><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> 
            {{number_format($tot_bal, 2, '.', ',')}} </h2>
            <h4>Total Balance</h4>
        </div>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="well dash-box">
            <h2><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 
            {{number_format($tot_count, 2, '.', ',')}}</h2>
            <h4>Total Num Invoices</h4>
        </div>
      </div>
    </div>
  </div>

  <table class="table table-striped" >
      <tr>
        <th width="14.2%">Date</th>
        <th width="14.2%">Sender Company</th>
        <th width="14.2%">Invoice#</th>
        <th width="14.2%">Amount</th>
        <th width="14.2%">VAT</th>
        <th width="14.2%">Paid</th>
        <th width="14.2%">Balance</th>
        <th></th>
        <th></th>
      </tr>
      @foreach($invoices as $invoice)
      <tr>
        <td>{{$invoice['created_at']}}</td>
        <td>{{$invoice['sender_company']['name']}}</td>
        <td>{{$invoice['invoice_num']}}</td>
        <td>{{number_format($invoice['amount'], 2, '.', ',')}}</td>
        <td>{{number_format($invoice['vat'], 2, '.', ',')}}</td>
        <td>{{number_format($invoice['paid'], 2, '.', ',')}}</td>
        <td>{{number_format($invoice['bal'], 2, '.', ',')}}</td>
        <td><a class="pull-right btn btn-default btn-xs" href="{{ route('invoice.show', ['id' => $invoice->id ]) }}">View Details</a></td>
        <td>
          {!!Form::open(['action' => ['InvoicesController@voidInvoice', $invoice->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
            {{Form::hidden('_method', 'PUT')}}
            {{Form::submit('Void Invoice', ['class' => 'btn btn-danger btn-xs'])}}
          {!! Form::close() !!}

        </td>
    </tr>
      @endforeach
  </table>
@else
  <p>No Invoice To Display</p>
@endif

{{ $invoices->links() }}


@endsection