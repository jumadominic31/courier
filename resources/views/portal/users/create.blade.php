@extends('layouts.cusapp')

@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Create User <a 
                href="{{ route('portal.users.index') }}"
                class="pull-right btn btn-default btn-xs">Go Back</a>
            </div>
            <div class="panel-body">
                {!! Form::open(['action' => 'CusportalController@cusstore', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
                    {{Form::label('station_id', 'Branch Name')}}
                    {{Form::select('station_id', ['' => ''] + $stations, 'station_id', ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('usertype', 'User Type')}}
                    {{Form::select('usertype', ['cusadmin' => 'Customer Admin', 'cusclerk' => 'Customer Clerk'], 'cusclerk', ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('status', 'Status')}}
                    {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control'])}}
                </div>
                {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
   
@endsection