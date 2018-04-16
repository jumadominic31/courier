@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Zone <a href="{{ route('zone.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>
                <div class="panel-body">
                {!!Form::open(['action' => ['ZonesController@update', $zone->id],'method' => 'POST'])!!}
                    <div class="form-group">
                        {{Form::label('name', 'Zone Name')}}
                        {{Form::text('name', $zone->name, ['class' => 'form-control', 'placeholder' => 'Zone Name'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('status', 'Zone Active Status')}}
                        {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], $zone->status, ['class' => 'form-control', 'placeholder' => 'Zone Active Status'])}}                    
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