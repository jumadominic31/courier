@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Contract
                <a href="{{ route('contracts.index') }}" class="pull-right btn btn-default">Go Back</a>

            <div class="panel-body">
                {!! Form::open(['action' => ['ContractsController@update', $contract->id],'method' => 'POST']) !!}
                    <div class="form-group">
                        {{Form::label('name', 'Contract Name')}}
                        {{Form::text('name', $contract->contract_num, ['class' => 'form-control', 'disabled' => 'true'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('company_id', 'Company Name')}}
                        {{Form::text('company_id', $contract['company']['name'], ['class' => 'form-control', 'disabled' => 'true'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('start_date', 'Start Date')}}
                        {{Form::text('start_date', $contract->start_date, ['class' => ' date form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('end_date', 'End Date')}}
                        {{Form::text('end_date', $contract->end_date, ['class' => ' date form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('min_charge', 'Minimum Charge')}}
                        {{Form::text('min_charge', $contract->min_charge, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('txns_limit', 'Shipments Limit')}}
                        {{Form::text('txns_limit', $contract->txns_limit, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('txn_cost_overlimit', 'Cost for Shipment Over Limit')}}
                        {{Form::text('txn_cost_overlimit', $contract->txn_cost_overlimit, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('big_luggage', 'Big Luggage Charge')}}
                        {{Form::text('big_luggage', $contract->big_luggage, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('out_coverage', 'Outside Coverage Charge')}}
                        {{Form::text('out_coverage', $contract->out_coverage, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('status', 'Status')}}
                        {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], $contract->status, ['class' => 'form-control'])}}
                    </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection