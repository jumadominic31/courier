@extends('layouts.cusapp')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Station Details <a 
                href="{{ route('cusbranches.index', ['id' => $company_id ]) }}"
                class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
                {!!Form::open(['action' => ['StationsController@updateCusbranch', $station->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('company_name', 'Company Name')}}
                    {{Form::text('company_name', $company_name, ['class' => 'form-control', 'placeholder' => 'Company Name', 'disabled' => 'true'])}}
                </div>
                <div class="form-group">
                    {{Form::label('name', 'Branch Name')}}
                    {{Form::text('name', $station->name, ['class' => 'form-control', 'placeholder' => 'Branch Name'])}}
                </div>
                <div class="form-group">
                    {{Form::label('status', 'Branch Active Status')}}
                    {{Form::select('status', ['' => '', 1 => 'Active', 0 => 'Inactive'], $station->status, ['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection