@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit ParcelType <a href="{{ route('parcel.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>
                <div class="panel-body">
                {!!Form::open(['action' => ['ParcelsController@update', $parceltype->id],'method' => 'POST'])!!}
                    <div class="form-group">
                        {{Form::label('name', 'ParcelType Name')}}
                        {{Form::text('name', $parceltype->name, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('unit', 'Unit')}}
                        {{Form::select('unit', ['unit' => 'Unit', 'kg' => 'Kilogrammes', 'litre' => 'Litres'], $parceltype->unit, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('rate', 'Rate in KShs.')}}
                        {{Form::text('rate', $parceltype->rate, ['class' => 'form-control'])}}
                    </div>
                    @if(Auth::user()->usertype == 'superadmin')
                        <div class="form-group">
                            {{Form::label('company_id', 'Company Name')}}
                            {{Form::select('company_id', $companies, '', ['class' => 'form-control'])}}
                        </div>
                    @endif
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection