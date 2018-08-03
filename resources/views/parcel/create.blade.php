@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Create ParcelType <a href="{{ route('parcel.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></strong></div>
                <div class="panel-body">
                    {!! Form::open(['action' => 'ParcelsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group">
                            {{Form::label('name', 'ParcelType Name*')}}
                            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'e.g. Box'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('unit', 'Unit*')}}
                            {{Form::select('unit', ['' => '', 'unit' => 'Unit', 'kg' => 'Kilogrammes', 'litre' => 'Litres'], '', ['class' => 'form-control'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('status', 'Active Status*')}}
                            {{Form::select('status', ['' => '', 1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control'])}}
                        </div>
                        @if(Auth::user()->usertype == 'superadmin')
                            <div class="form-group">
                                {{Form::label('company_id', 'Company Name')}}
                                {{Form::select('company_id', $companies, '', ['class' => 'form-control'])}}
                            </div>
                        @endif
                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>  
@endsection