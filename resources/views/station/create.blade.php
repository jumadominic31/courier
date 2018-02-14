@extends('layouts.app')

@section('content')
    <h1>Create Station</h1>
    <a href="{{ route('station.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'StationsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Station Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Station Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Company Active Status')}}
            {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control', 'placeholder' => 'Station Active Status'])}}
        </div>
        @if(Auth::user()->usertype == 'superadmin')
            <div class="form-group">
                {{Form::label('company_id', 'Company Name')}}
                {{Form::select('company_id', $companies, '', ['class' => 'form-control'])}}
            </div>
        @endif
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection