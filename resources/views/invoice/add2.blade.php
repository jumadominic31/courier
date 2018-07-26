@extends('layouts.app')

@section('content')

<div class="container"> 
    <h1>Create Invoice</h1>
    <a href="{{ route('invoice.index') }}" class="btn btn-success">Go back</a><br><br>
</div>

{!! Form::open(['action' => 'InvoicesController@storeInvoice2', 'method' => 'POST']) !!}
<div>
    <div class="input-group">
        <span class="input-group-addon" >Customer Company *</span>
        {{Form::select('company_id', ['' => ''] + $cuscompanies, '', ['class' => 'form-control'])}}
    </div>
    <div class="input-group">
        <span class="input-group-addon">Month</span>
        <input type="text" id="month" name="month" value="" class="month form-control"  aria-describedby="basic-addon1">
    </div>
</div>
{{Form::submit('Submit', ['class'=>'btn btn-primary btn-xl', 'id' => 'submit-btn'])}}
{!! Form::close() !!}


@endsection