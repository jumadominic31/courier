@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1>Manage Invoices </h1> </div>
<a href="{{ route('invoice.add2') }}" class="btn btn-success">Create Invoice - NEW</a><br>
<div><a class="pull-right btn btn-default" href="{{ route('invoice.index2') }}">Reset</a> </div>
<hr>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
  {!! Form::open(['action' => 'InvoicesController@getInvoices2', 'method' => 'POST']) !!}

    <table class="table" width="100%" table-layout="fixed">
      <tbody>
        <tr>
          <td width="33%"><div class="form-group">
            {{Form::label('invoice_num', 'Invoice #')}}
            {{Form::text('invoice_num', '', ['class' => 'form-control'])}}
          </div></td>
          <td width="33%"><div class="form-group">
            {{Form::label('company_id', 'Customer Company')}}
            {{Form::select('company_id', ['' => ''] + $cuscompanies ,'', ['class' => 'form-control'])}}
          </div></td>
          <td width="33%"><div class="form-group">
            {{Form::label('month', 'Invoice Month')}}
            {{Form::text('month', '', ['class' => 'month  form-control', 'placeholder' => 'yyyy-mm-dd'])}}
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
  <div>Dashboard</div>

  <table class="table table-striped" >
      <tr>
        <th >Creation Date</th>
        <th >Company</th>
        <th >Invoice#</th>
        <th >Month</th>
        <th >Amount</th>
        <th >VAT</th>
        <th></th>
        <th></th>
      </tr>
      @foreach($invoices as $invoice)
      <tr>
        <td>{{$invoice['created_at']}}</td>
        <td>{{$invoice['company']['name']}}</td>
        <td>{{$invoice['invoice_num']}}</td>
        <td>{{$invoice['month']}}</td>
        <td>{{number_format($invoice['total_charge'], 2, '.', ',')}}</td>
        <td>{{number_format($invoice['vat'], 2, '.', ',')}}</td>
        <td><a class="pull-right btn btn-default btn-xs" href="{{ route('invoice.print2', ['id' => $invoice->id ]) }}" target="_blank">View Details</a></td>
        <td>
          {!!Form::open(['action' => ['InvoicesController@voidInvoice', $invoice->id],'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure you want to void this invoice?")'])!!}
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


@endsection