@extends('layouts.app')

@section('content')
    <h1>Create ParcelType</h1>
    <a href="{{ route('parcel.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'ParcelsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'ParcelType Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'e.g. Box'])}}
        </div>
        <div class="form-group">
            {{Form::label('unit', 'Unit')}}
            {{Form::select('unit', ['unit' => 'Unit', 'kg' => 'Kilogrammes', 'litre' => 'Litres'], '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('rate', 'Rate in KShs.')}}
            {{Form::text('rate', '', ['class' => 'form-control'])}}
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