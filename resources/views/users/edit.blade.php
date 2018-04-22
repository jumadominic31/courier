@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit User Details <a 
                href="{{ route('users.index') }}"
                class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
              {!!Form::open(['action' => ['UsersController@update', $user->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('firstname', 'First Name')}}
                    {{Form::text('firstname', $user->firstname, ['class' => 'form-control', 'placeholder' => 'First Name'])}}
                </div>
                <div class="form-group">
                    {{Form::label('lastname', 'Last Name')}}
                    {{Form::text('lastname', $user->lastname, ['class' => 'form-control', 'placeholder' => 'Last Name'])}}
                </div>
                <div class="form-group">
                    {{Form::label('phone', 'Phone Number')}}
                    {{Form::text('phone', $user->phone, ['class' => 'form-control', 'placeholder' => 'Phone Number'])}}
                </div>
                <div class="form-group">
                    {{Form::label('email', 'Email')}}
                    {{Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
                </div>
                
                @if (Auth::user()->usertype == 'superadmin')
                    <div class="form-group">
                        {{Form::label('usertype', 'User Type')}}
                        {{Form::select('usertype', ['admin' => 'Admin', 'clerk' => 'Clerk', 'driver' => 'Driver', 'superadmin' => 'Superadmin'], $user->usertype, ['class' => 'form-control'])}}
                    </div>
                @else
                    <div class="form-group">
                        {{Form::label('usertype', 'User Type')}}
                        {{Form::text('usertype', $user->usertype, ['class' => 'form-control', 'disabled' => 'true'])}}
                    </div>
                @endif
                <div class="form-group">
                    {{Form::label('station_id', 'Station Name')}}
                    {{Form::select('station_id', ['' => ''] + $stations, $user->station_id, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('status', 'Status')}}
                    {{Form::select('status', [1 => 'Active', 0 => 'Inactive'], $user->status, ['class' => 'form-control'])}}
                </div>
                @if(Auth::user()->usertype == 'superadmin')
                    <div class="form-group">
                        {{Form::label('company_id', 'Company Name')}}
                        {{Form::select('company_id', $companies, '', ['class' => 'form-control'])}}
                    </div>
                @endif
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection