@extends('layouts.app')

@section('content')
    <h1>Create Zone</h1>
    <a href="{{ route('zone.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'ZonesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Zone Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Zone Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Zone Active Status')}}
            {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control', 'placeholder' => 'Zone Active Status'])}}
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