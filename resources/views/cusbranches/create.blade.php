@extends('layouts.app')

@section('content')
    <h1>Create User</h1>
    <a href="{{ route('customer.index') }}" class="pull-right btn btn-default">Go Back</a>
	<br>
    {!! Form::open(['action' => ['StationsController@cusbranchstore', $company_id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('company_name', 'Company Name')}}
            {{Form::text('company_name', $company_name, ['class' => 'form-control', 'placeholder' => 'Company Name', 'disabled' => 'true'])}}
        </div>
        <div class="form-group">
            {{Form::label('name', 'Branch Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Branch Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('building', 'Building Name')}}
            {{Form::text('building', '', ['class' => 'form-control', 'placeholder' => 'Building Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('floor_office', 'Floor no/Office no')}}
            {{Form::text('floor_office', '', ['class' => 'form-control', 'placeholder' => 'Floor no/Office no'])}}
        </div>
        <div class="form-group">
            {{Form::label('street', 'Street/Road Name')}}
            {{Form::text('street', '', ['class' => 'form-control', 'placeholder' => 'Street/Road Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('area', 'Area Name')}}
            {{Form::text('area', '', ['class' => 'form-control', 'placeholder' => 'Area Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Branch Active Status')}}
            {{Form::select('status', ['' => '', 1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control'])}}
        </div>
        {{Form::hidden('company_id', '')}}
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection