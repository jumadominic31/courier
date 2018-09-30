@extends('layouts.app')

@section('content')
    <h1>Create User</h1>
    <a href="{{ route('customer.index') }}" class="pull-right btn btn-default">Go Back</a>
	<br>
    {!! Form::open(['action' => ['CustomersController@cusstore', $company_id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('company_name', 'Company Name')}}
            {{Form::text('company_name', $company_name, ['class' => 'form-control', 'placeholder' => 'Company Name', 'disabled' => 'true'])}}
        </div>
        <div class="form-group">
            {{Form::label('username', 'Username')}}
            {{Form::text('username', '', ['class' => 'form-control', 'placeholder' => 'Username'])}}
        </div>
        <div class="form-group">
            {{Form::label('firstname', 'First Name')}}
            {{Form::text('firstname', '', ['class' => 'form-control', 'placeholder' => 'First Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('lastname', 'Last Name')}}
            {{Form::text('lastname', '', ['class' => 'form-control', 'placeholder' => 'Last Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('phone', 'Phone Number')}}
            {{Form::text('phone', '', ['class' => 'form-control', 'placeholder' => 'Format example 254722000000'])}}
        </div>
        <div class="form-group">
            {{Form::label('email', 'Email')}}
            {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('station_id', 'Branch/Department')}}
            {{Form::select('station_id', ['' => ''] + $stations, '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Status')}}
            {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection