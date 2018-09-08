@extends('layouts.app')

@section('content')
    <h1>Create Contract</h1>
    <a href="{{ route('contracts.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'ContractsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('company_id', 'Company Name')}}
            {{Form::select('company_id', ['' => ''] + $cuscompanies, '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('start_date', 'Start Date')}}
            {{Form::text('start_date', '', ['class' => ' date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
        </div>
        <div class="form-group">
            {{Form::label('end_date', 'End Date')}}
            {{Form::text('end_date', '', ['class' => ' date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
        </div>
        <div class="form-group">
            {{Form::label('min_charge', 'Minimum Charge')}}
            {{Form::text('min_charge', '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('txns_limit', 'Shipments Limit')}}
            {{Form::text('txns_limit', '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('txn_cost_overlimit', 'Cost for Shipment Over Limit')}}
            {{Form::text('txn_cost_overlimit', '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('big_luggage', 'Big Luggage Charge')}}
            {{Form::text('big_luggage', '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('out_coverage', 'Outside Coverage Charge')}}
            {{Form::text('out_coverage', '', ['class' => 'form-control'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection