@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Vehicle <a href="{{ route('vehicle.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>
                <div class="panel-body">
                {!!Form::open(['action' => ['VehiclesController@update', $vehicle->id],'method' => 'POST'])!!}
                    <div class="form-group">
                        {{Form::label('name', 'Vehicle Name')}}
                        {{Form::text('name', $vehicle->name, ['class' => 'form-control', 'placeholder' => 'Vehicle Name'])}}
                    </div>
                    @if(Auth::user()->usertype == 'superadmin')
                        <?php
                            $companies[0] = 'Select Company';
                        ?>
                        <div class="form-group">
                            {{Form::label('company_id', 'Company Name')}}
                            {{Form::select('company_id', $companies, $vehicle->company_id, ['class' => 'form-control'])}}
                        </div>
                    @endif
                    <div class="form-group">
                        {{Form::label('status', 'Vehicle Active Status')}}
                        {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], $vehicle->status, ['class' => 'form-control', 'placeholder' => 'Vehicle Active Status'])}}                    
                    </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection