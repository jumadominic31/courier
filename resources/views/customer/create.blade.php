@extends('layouts.app')

@section('content')
    <h1>Add Customer</h1>
    <a href="{{ route('customer.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'CustomersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Company Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Company Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('address', 'Address')}}
            {{Form::text('address', '', ['class' => 'form-control', 'placeholder' => 'Company Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('city', 'City/Town')}}
            {{Form::text('city', '', ['class' => 'form-control', 'placeholder' => 'City/Town'])}}
        </div>
        <div class="form-group">
            {{Form::label('zone_id', 'Zone')}}
            {{Form::select('zone_id', [''=>''] + $zones, '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('pin', 'PIN Number')}}
            {{Form::text('pin', '', ['class' => 'form-control', 'placeholder' => 'PIN Number'])}}
        </div>
        <div class="form-group">
            {{Form::label('phone', 'Telephone')}}
            {{Form::text('phone', '', ['class' => 'form-control', 'placeholder' => 'Telephone'])}}
        </div>
        <div class="form-group">
            {{Form::label('email', 'Email Address')}}
            {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('logo', 'Logo Image')}}
            {{Form::file('logo' )}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Company Active Status')}}
            {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control', 'placeholder' => 'Company Active Status'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection