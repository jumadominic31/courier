@extends('layouts.app')

@section('content')

<div class="container">
	<h1>Show Invoice</h1>
	<a href="{{ route('invoice.index2') }}" class="btn btn-success">Go back</a><br><br>
    <a href="{{ route('invoice.print2', ['id' => $invoice->id]) }}" class="btn btn-success" target="_blank">Print Invoice</a><br><br>
</div>

<div class="container"> 
    <div class="row">
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Invoice Num</h3>
                </div>
                <div class="panel-body">
                    {{$invoice->invoice_num}}
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Company</h3>
                </div>
                <div class="panel-body">
                    {{$invoice['company']['name']}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Amount</h3>
                </div>
                <div class="panel-body">
                    {{number_format($invoice->total_charge, 2, '.', ',')}}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">VAT</h3>
                </div>
                <div class="panel-body">
                    {{number_format($invoice->vat, 2, '.', ',')}}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Paid</h3>
                </div>
                <div class="panel-body">
                    {{number_format($invoice->paid, 2, '.', ',')}}
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Balance</h3>
                </div>
                <div class="panel-body">
                    {{number_format($invoice->bal, 2, '.', ',')}}
                </div>
            </div>
        </div>
    </div>
</div>
		
@endsection